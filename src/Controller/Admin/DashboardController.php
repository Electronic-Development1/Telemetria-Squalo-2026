<?php

namespace App\Controller\Admin;

use App\Controller\Admin\TimerMetaCrudController;
use App\Controller\Admin\TimerPitsCrudController;
use App\Controller\Admin\TimerCambioCrudController;
use App\Controller\Admin\TimerDanosCrudController;
use App\Entity\Evento;
use App\Entity\Piloto;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;


#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private $doctrine;

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrine = $registry;
    }
   
    /**
     * Pantalla de cronómetros.
     * Pasa la lista de pilotos y el evento activo (el más reciente) a la vista.
     */
    #[Route('/time', name: 'addCronometros', methods: ['GET'])]
    public function addCronometros(): Response
    {
        $em = $this->doctrine->getManager();

        $pilotos = $em->getRepository(Piloto::class)->findAll();

        // Toma el evento más reciente como "activo"
        $evento = $em->getRepository(Evento::class)
            ->findOneBy([], ['id' => 'DESC']);

        $eventoActivo = $evento ? $evento->getId() : '';

        return $this->render('admin/cronometro.html.twig', [
            'pilotos'      => $pilotos,
            'eventoActivo' => $eventoActivo,
        ]);
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Squalo');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(EventoCrudController::class, 'Competencias', 'fa fa-tags');
        yield MenuItem::linkTo(PilotoCrudController::class, 'Piloto', 'fa fa-tags');
        yield MenuItem::linkTo(TimerPilotoCrudController::class, 'Tiempos de Piloto', 'fa fa-tags');

        yield MenuItem::subMenu('-------', 'fa fa-clock-o')->setSubItems([
            MenuItem::linkTo(TimerMetaCrudController::class, 'META', 'fa fa-flag'),
            MenuItem::linkTo(TimerPitsCrudController::class, 'PITS', 'fa fa-wrench'),
            MenuItem::linkTo(TimerCambioCrudController::class, 'Cambio de Piloto', 'fa fa-user'),
            MenuItem::linkTo(TimerDanosCrudController::class, 'Daños', 'fa fa-warning'),
        ]);
    }
}
