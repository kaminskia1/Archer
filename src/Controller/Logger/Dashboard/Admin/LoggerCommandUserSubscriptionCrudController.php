<?php

namespace App\Controller\Logger\Dashboard\Admin;

use App\Entity\Logger\LoggerCommandUserSubscription;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class LoggerCommandUserSubscriptionCrudController
 *
 * @package App\Controller\Logger\Dashboard\Admin
 */
class LoggerCommandUserSubscriptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LoggerCommandUserSubscription::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Subscription Command Logs')
            ->setEntityLabelInPlural('Commands')
            ->setEntityLabelInSingular(
                fn(?LoggerCommandUserSubscription $command, string $pageName = "0") => $command ? ("Subscription: " . $command->getId()) : 'Subscription'

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
        yield AssociationField::new('subscription')->hideOnForm();
        yield NumberField::new('response', 'Minutes Till Exp')->hideOnForm();
        yield BooleanField::new('flagged')->hideOnForm()->setFormTypeOption('disabled', true)->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield TextField::new('flagType', 'Issued Ban')->onlyOnDetail();
    }
}
