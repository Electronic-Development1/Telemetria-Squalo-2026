<?php

namespace App\Controller\Admin;

use App\Entity\TimerPiloto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TimerPilotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimerPiloto::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('evento'),
            AssociationField::new('piloto'),
            TextField::new('cronometro'),
            NumberField::new('numero_vueltas'),
            TextField::new('diff')
        ];
    }
}
