<?php

namespace App\Controller\Admin;

use App\Entity\TypeTransaction;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeTransactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeTransaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Intitulé du type de transaction');
        yield IntegerField::new('qte', 'Nb de transactions')->hideOnForm();
    }
}
