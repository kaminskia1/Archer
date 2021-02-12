<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class UserCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Users')
            ->setEntityLabelInSingular(
                fn (?User $user, string $pageName = "0") => $user ? ($user->getUuid() . ( $user->getNickname() != "" ? " (" . $user->getNickname() . ")" : "" ) ) : 'User'
            )
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Users')
            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->remove(CRUD::PAGE_INDEX, Action::DELETE);
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('uuid', "User ID")->hideOnForm(),
            IdField::new('id', "Internal ID")->onlyOnDetail(),
            TextField::new('nickname'),
            DateTimeField::new('registrationDate')->hideOnForm(),
            TextField::new('plainPassword', "New Password")->onlyOnForms()->setRequired( $pageName == "new" ),
            ChoiceField::new('roles')->allowMultipleChoices(true)->setChoices([
                "User" => "ROLE_USER",
                "Banned" => "ROLE_BANNED",
                "Subscriber" => "ROLE_SUBSCRIBER",
                "Admin" => "ROLE_ADMIN"
            ]),
            AssociationField::new('registrationCode')->onlyWhenCreating()->setRequired(true),
            TextField::new('hwid', 'Hardware ID')->onlyOnDetail(),
            DateTimeField::new('lastWebsiteLoginDate', "Last Site Login")->hideOnForm(),
            DateTimeField::new('lastLoaderLoginDate', "Last Loader Login")->onlyOnDetail(),
            TextareaField::new('staffNote'),
        ];
    }

}