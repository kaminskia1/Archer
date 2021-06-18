<?php

namespace App\Controller\Logger\Dashboard\Admin;

use App\Entity\Logger\LoggerCommandAuth;
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

/**
 * Class LoggerCommandAuthCrudController
 *
 * @package App\Controller\Logger\Dashboard\Admin
 */
class LoggerCommandAuthCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LoggerCommandAuth::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Auth Command Logs')
            ->setEntityLabelInPlural('Commands')
            ->setEntityLabelInSingular(
                fn(?LoggerCommandAuth $command, string $pageName = "0") => $command ? ("Command: " . $command->getId()) : 'Command'

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
        yield AssociationField::new('user')->hideOnForm();
        yield AssociationField::new('package')->hideOnForm();
        yield TextField::new('ip')->hideOnForm();
        yield TextField::new('response')->hideOnForm();
        yield DateTimeField::new('subscriptionExpiry')->onlyOnDetail();
        yield TextField::new('providedHwid')->onlyOnDetail();
        yield NumberField::new('currentInfractionPoints')->onlyOnDetail();
        yield TextField::new('flagType')->onlyOnDetail();
        yield BooleanField::new('flagged')->hideOnForm()->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);;
        yield BooleanField::new('bannedAtAttempt')->hideOnForm()->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield DateTimeField::new('execution', 'Time')->hideOnForm();
    }
}
