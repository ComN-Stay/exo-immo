<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('fullname', 'Nom')->onlyOnIndex();
        yield ChoiceField::new('gender', 'Civilité')
            ->onlyOnForms()
            ->setColumns(2)
            ->setChoices([
                'Monsieur' => 'M.',
                'Madame' => 'Mme',
            ]);
        yield TextField::new('firstname', 'Prénom')
            ->hideOnIndex()
            ->setColumns(5);
        yield TextField::new('lastname', 'Nom')
            ->hideOnIndex()
            ->setColumns(5);
        yield TextField::new('company', 'Société');
        yield TextField::new('address', 'Adresse')
            ->hideOnIndex()
            ->setColumns(6);
        yield TextField::new('address_comp', 'Complément d\'adresse')
            ->hideOnIndex()
            ->setColumns(6);
        yield TextField::new('zipcode', 'Code postal')
            ->setColumns(2);
        yield TextField::new('town', 'Ville')
            ->setColumns(5);
        yield TextField::new('country', 'Pays')
            ->setColumns(5);
        yield EmailField::new('email', 'Adresse email');
        yield TelephoneField::new('phone', 'Téléphone fixe')
            ->hideOnIndex()
            ->setColumns(6);
        yield TelephoneField::new('mobile', 'Téléphone mobile')
            ->hideOnIndex()
            ->setColumns(6);
        yield TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation mot de passe'],
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();
        yield ChoiceField::new('roles', 'Accès')
            ->onlyOnForms()
            ->allowMultipleChoices()
            ->setChoices([
                'Accès espace client autorisé' => 'ROLE_CLIENT',
                'Accès espace client non autorisé' => 'ROLE_USER',
            ]);
    }
}
