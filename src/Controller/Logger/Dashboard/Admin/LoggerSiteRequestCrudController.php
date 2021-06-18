<?php

namespace App\Controller\Logger\Dashboard\Admin;

use App\Entity\Logger\LoggerSiteAuthLogin;
use App\Entity\Logger\LoggerSiteRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class LoggerSiteRequestCrudController
 *
 * @package App\Controller\Logger\Admin
 */
class LoggerSiteRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LoggerSiteRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Site Request Logs')
            ->setEntityLabelInPlural('Requests')
            ->setEntityLabelInSingular(
                fn(?LoggerSiteRequest $command, string $pageName = "0") => $command ? ("Request: " . $command->getId()) : 'Request'

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
        yield TextField::new('ip', 'IP')->hideOnForm();
        yield TextField::new('method')->hideOnForm();
        yield TextField::new('basePath', 'Path')->hideOnForm();
        yield TextField::new('query')->onlyOnDetail();
        yield TextField::new('route')->hideOnForm();
        yield TextField::new('userAgent')->onlyOnDetail();
        yield TextField::new('host')->onlyOnDetail();
        yield TextField::new('locale')->onlyOnDetail();
        yield BooleanField::new('isSecure', 'HTTPS')->onlyOnDetail()->setFormTypeOption('disabled', true);;
        yield DateTimeField::new('execution', 'Time')->hideOnForm();
    }
}
