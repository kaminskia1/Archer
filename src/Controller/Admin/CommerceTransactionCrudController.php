<?php

namespace App\Controller\Admin;

use App\Entity\CommerceTransaction;
use App\Entity\CommerceUserSubscription;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceTransactionCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceTransactionCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Transactions')
            ->setEntityLabelInPlural('Transactions')
            ->setEntityLabelInSingular(
                fn (?CommerceTransaction $transaction, string $pageName = "0") => $transaction ? ("Transaction: " . $transaction->getId() ): 'Transaction'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CommerceTransaction::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield AssociationField::new('user')->setRequired(true);
        yield AssociationField::new('commerceGatewayInstance', 'Gateway');
        yield AssociationField::new('commercePurchase', 'Purchase');
        yield AssociationField::new('commerceInvoice', 'Invoice');
        yield NumberField::new('amount', 'Amount Paid')
            ->setNumDecimals(2)
            ->setStoredAsString(false)
            ->formatValue(function ($value) {
                return $_ENV['COMMERCE_CURRENCY_SYMBOL'] . $value;
            })->setTextAlign('right');yield TextField::new('gatewayData')->hideOnIndex();
        yield DateTimeField::new('creationDateTime', 'Creation Date')->setRequired(true);
        yield TextField::new('staffMessage');
    }
}
