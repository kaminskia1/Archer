<?php

namespace App\Controller\Logger\Admin;

use App\Entity\Logger\LoggerSiteAuthLogin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LoggerSiteAuthLoginCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LoggerSiteAuthLogin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Site Auth Logs')
            ->setEntityLabelInPlural('Logins')
            ->setEntityLabelInSingular(
                fn(?LoggerSiteAuthLogin $command, string $pageName = "0") => $command ? ("Login: " . $command->getId()) : 'Login'

            )
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);

        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);
        $actions->remove(Crud::PAGE_DETAIL, Action::DELETE);
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('uuid', 'UUID')->hideOnForm();
        yield TextField::new('ip', 'IP')->hideOnForm();
        yield TextField::new('basePath', 'Path')->onlyOnDetail();
        yield TextField::new('userAgent')->onlyOnDetail();
        yield TextField::new('language')->onlyOnDetail();
        yield BooleanField::new('isUserReal', 'User Real')
            ->onlyOnDetail()->setFormTypeOption('disabled', true)
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield BooleanField::new('isAuthSuccessful', 'Successful')
            ->hideOnForm()->setFormTypeOption('disabled', true)
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield DateTimeField::new('execution', 'Time')->hideOnForm();
    }
}
