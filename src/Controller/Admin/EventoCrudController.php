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

        $verInformes = Action::new('verInformes', 'Ver Informes', 'fa fa-bar-chart')
            ->linkToCrudAction('verInformes');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            //->add(Crud::PAGE_INDEX, $verFactura)
            ->add(Crud::PAGE_INDEX, $addCronometros)
            ->add(Crud::PAGE_INDEX, $verInformes);
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

    $piloto      = $pilotoId     ? $em->getRepository(\App\Entity\Piloto::class)->find($pilotoId)     : null;
    $pilotoEntra = $pilotoEntraId ? $em->getRepository(\App\Entity\Piloto::class)->find($pilotoEntraId) : null;
    $evento      = $eventoActivo ? $em->getRepository(\App\Entity\Evento::class)->find($eventoActivo)  : null;

    $dtInicio = new \DateTime($fechaInicio);
    $dtFinal  = new \DateTime($fechaFinal);

    $seg = abs($dtFinal->getTimestamp() - $dtInicio->getTimestamp());
    $tiempoStr = floor($seg / 60) . ':' . str_pad($seg % 60, 2, '0', STR_PAD_LEFT) . ' sg';

    switch ($cronometro) {

        case 'Meta':
            $record = new \App\Entity\TimerMeta();
            $record->setPiloto($piloto);
            $record->setNumeroVuelta($numeroVuelta);
            $record->setFechaInicio($dtInicio);
            $record->setFechaFinal($dtFinal);
            $record->setTiempo($tiempoStr);
            $record->setEvento($evento);
            break;

        case 'PITS':
            $record = new \App\Entity\TimerPits();
            $record->setPiloto($piloto);
            $record->setNumeroVuelta($numeroVuelta);
            $record->setFechaInicio($dtInicio);
            $record->setFechaFinal($dtFinal);
            $record->setTiempo($tiempoStr);
            $record->setEvento($evento);
            break;

        case 'Cambio':
            $record = new \App\Entity\TimerCambio();
            $record->setPilotoSale($piloto);
            $record->setPilotoEntra($pilotoEntra);
            $record->setFechaInicio($dtInicio);
            $record->setFechaFinal($dtFinal);
            $record->setTiempo($tiempoStr);
            $record->setEvento($evento);
            break;

        case 'Danos':
            $record = new \App\Entity\TimerDanos();
            $record->setPiloto($piloto);
            $record->setNumeroVuelta($numeroVuelta);
            $record->setFechaInicio($dtInicio);
            $record->setFechaFinal($dtFinal);
            $record->setTiempo($tiempoStr);
            $record->setEvento($evento);
            break;

        default:
            return $this->json(['status' => false, 'error' => 'Cronómetro desconocido'], 400);
    }

    $em->persist($record);
    $em->flush();

    return $this->json(['status' => true]);
}

    #[AdminRoute(path: '/verInformes', name: 'verInformes')]
    public function verInformes(AdminContext $context, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $pilotosRepository = $em->getRepository(Piloto::class);

        $pilotos = $pilotosRepository->findAll();
        return $this->render('admin/informe .html.twig', [
            'pilotos' => $pilotos
        ]);
    }
}
