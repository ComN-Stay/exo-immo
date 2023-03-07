<?php

namespace App\Controller\Admin;

use App\Entity\TypeBien;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeBienCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeBien::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Intitulé du type de bien');
        yield IntegerField::new('qte', 'Nb de biens')->hideOnForm();
    }
}
