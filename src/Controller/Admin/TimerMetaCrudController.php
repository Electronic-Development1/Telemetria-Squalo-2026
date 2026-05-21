<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Entity\TimerMeta;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;


class TimerMetaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimerMeta::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        AssociationField::new('evento'),
        AssociationField::new('piloto'),
        NumberField::new('numero_vuelta', 'Vuelta'),
        TextField::new('tiempo', 'Tiempo'),
        DateTimeField::new('fecha_inicio', 'Inicio'),
        DateTimeField::new('fecha_final', 'Final'),
    ];
}
}
