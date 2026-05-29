<?php

namespace App\Controller\Admin;

use App\Entity\TimerPiloto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class TimerPilotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimerPiloto::class;
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('cronometro')
            ->add('numero_vueltas')
            ->add('piloto')
            ->add('piloto_entra')
            ->add('evento')
            ->add(propertyNameOrFilter: 'fecha_inicio')
            ->add(propertyNameOrFilter: 'fecha_final')

        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('evento'),
            AssociationField::new('piloto'),
            AssociationField::new('piloto_entra'),
            TextField::new('cronometro'),
            NumberField::new('numero_vueltas'),
            TextField::new('diff')
        ];
    }
}
