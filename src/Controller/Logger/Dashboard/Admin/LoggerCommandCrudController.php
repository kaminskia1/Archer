<?php

namespace App\Controller\Logger\Dashboard\Admin;

use App\Entity\Logger\LoggerCommand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class LoggerCommandCrudController
 *
 * @package App\Controller\Logger\Admin
 */
class LoggerCommandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LoggerCommand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Command Logs')
            ->setEntityLabelInPlural('Commands')
            ->setEntityLabelInSingular(
                fn(?LoggerCommand $command, string $pageName = "0") => $command ? ("Command: " . $command->getName()) : 'Command'

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
        yield TextField::new('name')->hideOnForm();
        yield DateTimeField::new('execution', 'Time')->hideOnForm();
    }
}
