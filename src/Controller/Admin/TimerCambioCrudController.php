<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Entity\TimerCambio;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class TimerCambioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimerCambio::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        AssociationField::new('evento'),
        AssociationField::new('piloto_sale', 'Piloto Sale'),
        AssociationField::new('piloto_entra', 'Piloto Entra'),
        TextField::new('tiempo', 'Tiempo'),
        DateTimeField::new('fecha_inicio', 'Inicio'),
        DateTimeField::new('fecha_final', 'Final'),
    ];
}
}
