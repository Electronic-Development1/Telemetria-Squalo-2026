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

    #[Route(path: '/cronometros/api/timer', name: 'cronometros_api_timer',  methods: ['POST'])]
    public function cronometrosApiTimer(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $pilotoRepository = $em->getRepository(Piloto::class);
        $eventoRepository = $em->getRepository(Evento::class);

        $idPiloto = $request->request->get('piloto');
        $numeroVuelta = $request->request->get('numeroVuelta');
        $cronometro = $request->request->get('cronometro');
        $fecha_inicio = $request->request->get('fecha_inicio');
        $fecha_final = $request->request->get('fecha_final');
        $eventoActivo = $request->request->get('eventoActivo');
        $piloto = $pilotoRepository->find($idPiloto);
        $evento = $eventoRepository->find($eventoActivo);

        $timer = new TimerPiloto();
        $timer->setPiloto($piloto);
        $timer->setEvento($evento);
        $timer->setCronometro($cronometro);
        $timer->setFechaFinal(new DateTime($fecha_final));
        $timer->setFechaInicio(new DateTime($fecha_inicio));
        $timer->setNumeroVueltas($numeroVuelta);
        $em->persist($timer);
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
