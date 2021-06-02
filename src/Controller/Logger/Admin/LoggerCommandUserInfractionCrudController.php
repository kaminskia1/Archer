<?php

namespace App\Controller\Logger\Admin;

use App\Entity\Logger\LoggerCommandUserInfraction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LoggerCommandUserInfractionCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Infraction Command Logs')
            ->setEntityLabelInPlural('Commands')
            ->setEntityLabelInSingular(
                fn (?LoggerCommandUserInfraction $command, string $pageName = "0") => $command ? ("Infraction: " . $command->getId() ): 'Infraction'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ;
    }

    public static function getEntityFqcn(): string
    {
        return LoggerCommandUserInfraction::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->remove(Crud::PAGE_INDEX,Action::NEW);
        $actions->remove(Crud::PAGE_INDEX,Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX,Action::DELETE);

        $actions->remove(Crud::PAGE_DETAIL,Action::EDIT);
        $actions->remove(Crud::PAGE_DETAIL,Action::DELETE);
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield AssociationField::new('user')->hideOnForm();
        yield NumberField::new('previousPoints', 'Prior Points')->hideOnForm();
        yield NumberField::new('addedPoints', 'Issued Points')->hideOnForm();
        yield TextField::new('type')->onlyOnDetail();
        yield BooleanField::new('banIssued', 'Issued Ban')->hideOnForm()->setFormTypeOption('disabled', true)->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);;
        yield DateTimeField::new('execution', 'Time')->hideOnForm();
    }
}
