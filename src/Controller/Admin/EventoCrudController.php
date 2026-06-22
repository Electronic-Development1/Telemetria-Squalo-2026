<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use App\Entity\Piloto;
use App\Entity\TimerPiloto;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventoCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('fecha_realizacion'),
            ChoiceField::new('tipo')->setChoices([
                'Entrenamiento Udea' => 1,
                'Competencia VTH' => 2,

            ]),
            AssociationField::new('pilotos'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $addCronometros = Action::new('addCronometros', 'Add Cronometros', 'fa fa-clock-o')
            ->linkToCrudAction('addCronometros');

        $addCronometrosEntrenamiento = Action::new('addCronometrosEntrenamiento', 'Add Entrenamiento(Cronometro)', 'fa fa-clock-o')
            ->linkToCrudAction('addCronometrosEntrenamiento');

        $verInformes = Action::new('verInformes', 'Ver Informes', 'fa fa-bar-chart')
            ->linkToCrudAction('verInformes');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            //->add(Crud::PAGE_INDEX, $verFactura)
            ->add(Crud::PAGE_INDEX, $addCronometros)
            ->add(Crud::PAGE_INDEX, $addCronometrosEntrenamiento)
            ->add(Crud::PAGE_INDEX, $verInformes);
    }


    #[AdminRoute(path: '/entrenamiento/cronometros', name: 'addCronometros_entrenamiento')]
    public function addCronometrosEntrenamiento(AdminContext $context, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $eventoActivo = $context->getEntity()->getInstance();


        $pilotosRepository = $em->getRepository(Piloto::class);
        $pilotos = $pilotosRepository->findAll();

        return $this->render('admin/cronometro_entrenamientos.html.twig', [
            'pilotos' => $pilotos,
            'eventoActivo' => $eventoActivo
        ]);
    }


    #[AdminRoute(path: '/cronometros', name: 'addCronometros')]
    public function addCronometros(AdminContext $context, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $eventoActivo = $context->getEntity()->getInstance();


        $pilotosRepository = $em->getRepository(Piloto::class);

        $pilotos = $pilotosRepository->findAll();
        return $this->render('admin/cronometro.html.twig', [
            'pilotos' => $pilotos,
            'eventoActivo' => $eventoActivo
        ]);
    }

    #[Route(path: '/cronometros/api/timer', name: 'cronometros_api_timer', methods: ['POST'])]
    public function cronometrosApiTimer(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();

        $cronometro   = $request->request->get('cronometro');
        $pilotoId     = $request->request->get('piloto');
        $pilotoEntraId = $request->request->get('piloto_entra');
        $numeroVuelta = (int) $request->request->get('numeroVuelta', 1);
        $fechaInicio  = $request->request->get('fecha_inicio');
        $fechaFinal   = $request->request->get('fecha_final');
        $eventoActivo = $request->request->get('eventoActivo');

        $piloto      = $pilotoId     ? $em->getRepository(Piloto::class)->find($pilotoId)     : null;
        $pilotoEntra = $pilotoEntraId ? $em->getRepository(Piloto::class)->find($pilotoEntraId) : null;
        $evento      = $eventoActivo ? $em->getRepository(Evento::class)->find($eventoActivo)  : null;

        $dtInicio = new \DateTime($fechaInicio);
        $dtFinal  = new \DateTime($fechaFinal);

        $seg = abs($dtFinal->getTimestamp() - $dtInicio->getTimestamp());
        $tiempoStr = floor($seg / 60) . ':' . str_pad($seg % 60, 2, '0', STR_PAD_LEFT) . ' sg';
        $record = new TimerPiloto();
        $record->setPiloto($piloto);
        $record->setPilotoEntra($pilotoEntra);
        $record->setNumeroVueltas($numeroVuelta);
        $record->setFechaInicio($dtInicio);
        $record->setFechaFinal($dtFinal);
        $record->setCronometro($cronometro);
        $record->setEvento($evento);
        $em->persist($record);
        $em->flush();

        return $this->json(['status' => true]);
    }

    #[AdminRoute(path: '/verInformes', name: 'verInformes')]
    public function verInformes(AdminContext $context, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        // Pasar todos los eventos para el selector del informe
        $eventos = $em->getRepository(Evento::class)->findBy([], ['id' => 'DESC']);

        // Evento activo = el que se seleccionó en la lista
        $eventoActivo = $context->getEntity()->getInstance();
        $eventoActivoId = $eventoActivo ? $eventoActivo->getId() : null;

        return $this->render('admin/informe.html.twig', [
            'eventos'      => $eventos,
            'eventoActivo' => $eventoActivoId,
        ]);
    }

    #[Route(path: '/cronometros/api/informe-data', name: 'cronometros_api_informe_data', methods: ['GET'])]
public function informeData(ManagerRegistry $doctrine, Request $request): JsonResponse
{
    $em = $doctrine->getManager();
    $eventoId = $request->query->get('evento');

    if (!$eventoId) {
        return new JsonResponse(['error' => 'evento requerido'], 400);
    }

    $registrosMeta = $em->getRepository(TimerPiloto::class)
        ->findBy(['evento' => $eventoId], ['numero_vuelta' => 'ASC']);

    $dataMeta = [];
    foreach ($registrosMeta as $r) {
        $tiempoStr = $r->getTiempo() ?? '0:00 sg';
        preg_match('/(\d+):(\d+)/', $tiempoStr, $m);
        $segundos = isset($m[1]) ? (int)$m[1] * 60 + (int)$m[2] : 0;
        $dataMeta[] = [
            'vuelta'            => $r->getNumeroVuelta(),
            'piloto'            => $r->getPiloto()?->getNombre() ?? 'Sin piloto',
            'tiempo'            => $tiempoStr,
            'segundos'          => $segundos,
            'fecha_inicio_unix' => $r->getFechaInicio()?->getTimestamp(),
        ];
    }

    // ── Gráfico de barras: timer_pits ─────────────────────────────────────
    $registrosPits = $em->getRepository(TimerPiloto::class)
        ->findBy(['evento' => $eventoId], ['numero_vuelta' => 'ASC']);

    $dataPits = [];
    foreach ($registrosPits as $r) {
        $tiempoStr = $r->getTiempo() ?? '0:00 sg';
        preg_match('/(\d+):(\d+)/', $tiempoStr, $m);
        $segundos = isset($m[1]) ? (int)$m[1] * 60 + (int)$m[2] : 0;
        $dataPits[] = [
            'vuelta'   => $r->getNumeroVuelta(),
            'piloto'   => $r->getPiloto()?->getNombre() ?? 'Sin piloto',
            'tiempo'   => $tiempoStr,
            'segundos' => $segundos,
        ];
    }

    return new JsonResponse([
        'meta' => $dataMeta,
        'pits' => $dataPits,
    ]);
    }
}