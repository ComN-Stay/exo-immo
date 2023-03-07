<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Informations générales')
            ->setIcon('info');
        yield AssociationField::new('owner', 'Propriétaire')
            ->setColumns(4);
        yield AssociationField::new('type_bien', 'Type de bien')
            ->setColumns(4);
        yield AssociationField::new('transaction_type', 'Type de transaction')
            ->setColumns(4);
        yield TextField::new('title', 'Titre');
        yield TextEditorField::new('resume', 'Résumé')
            ->hideOnIndex();
        yield TextEditorField::new('description', 'Description')
            ->hideOnIndex();
        yield MoneyField::new('price', 'Tarif / loyer')
            ->setCurrency('EUR');
        yield FormField::addTab('Localisation')
            ->setIcon('earth');
        yield TextField::new('address', 'Adresse')
            ->hideOnIndex();
        yield TextField::new('address_comp', 'Complément d\'adresse')
            ->hideOnIndex();
        yield TextField::new('zipcode', 'Code postal')
            ->setColumns(2);
        yield TextField::new('town', 'Ville')
            ->setColumns(5);
        yield TextField::new('country', 'Pays')
            ->setColumns(5)
            ->hideOnIndex();
        yield TextField::new('latitude', 'Latitude')
            ->setColumns(6)
            ->hideOnIndex();
        yield TextField::new('longitude', 'Longitude')
            ->setColumns(6)
            ->hideOnIndex();
        if (Crud::PAGE_NEW !== $pageName) {
            yield FormField::addTab('Photos')
                ->setIcon('photo');
            
        }
    }
}
