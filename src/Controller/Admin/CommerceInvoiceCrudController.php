<?php

namespace App\Controller\Admin;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceInvoiceCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceInvoiceCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Invoices')
            ->setEntityLabelInPlural('Invoices')
            ->setEntityLabelInSingular(
                fn (?CommerceInvoice $invoice, string $pageName = "0") => $invoice ? ("Invoice: " . $invoice->getId() ): 'Invoice'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CommerceInvoice::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield AssociationField::new('user', 'User');
        yield ChoiceField::new('paymentState', 'Invoice State')->setChoices(
            [
                'Open' => CommerceInvoicePaymentStateEnum::INVOICE_OPEN,
                'Paid' => CommerceInvoicePaymentStateEnum::INVOICE_PAID,
                'Cancelled' => CommerceInvoicePaymentStateEnum::INVOICE_CANCELLED,
                'Expired' => CommerceInvoicePaymentStateEnum::INVOICE_EXPIRED
            ]
        );
        yield AssociationField::new('commercePackage', 'Package');
        yield AssociationField::new('commerceGatewayType', 'Gateway Type')->onlyOnDetail();
        yield AssociationField::new('commerceGatewayInstance', 'Gateway Instance')->hideOnIndex();
        yield AssociationField::new('discountCode', 'Discount Code')->hideOnIndex();
        yield NumberField::new('price')
            ->setNumDecimals(2)
            ->setStoredAsString(false)
            ->formatValue(function ($value) {
                return $_ENV['COMMERCE_CURRENCY_SYMBOL'] . $value;
            })->setTextAlign('right');
        yield DateTimeField::new('paidOn', 'Date Paid');
        yield DateIntervalField::new('durationDateInterval', 'Package Duration')->hideOnIndex();
        yield TextField::new('staffMessage');
    }
}
