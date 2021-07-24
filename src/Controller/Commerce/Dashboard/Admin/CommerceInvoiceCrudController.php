<?php

namespace App\Controller\Commerce\Dashboard\Admin;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class CommerceInvoiceCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceInvoiceCrudController extends AbstractCrudController
{

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return CommerceInvoice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Invoices')
            ->setEntityLabelInPlural('Invoices')
            ->setEntityLabelInSingular(
                fn(?CommerceInvoice $invoice, string $pageName = "0") => $invoice ? ("Invoice: " .
                    $invoice->getId()) : 'Invoice'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction(Action::DETAIL));
        $actions->add(
            Crud::PAGE_INDEX,
            Action::new('approveAction', 'Approve')
                ->linkToCrudAction('approveAction')
                ->displayIf(static function (CommerceInvoice $inv) {
                    return $inv->isOpen() || $inv->isPending();
                }));
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "ID")->hideOnForm();
        yield AssociationField::new('user', 'User');
        yield ChoiceField::new('paymentState', 'Invoice State')->setChoices(
            [
                'Open' => CommerceInvoicePaymentStateEnum::INVOICE_OPEN,
                'Pending' => CommerceInvoicePaymentStateEnum::INVOICE_PENDING,
                'Paid' => CommerceInvoicePaymentStateEnum::INVOICE_PAID,
                'Cancelled' => CommerceInvoicePaymentStateEnum::INVOICE_CANCELLED,
                'Expired' => CommerceInvoicePaymentStateEnum::INVOICE_EXPIRED
            ]
        );
        yield ChoiceField::new('type')->setChoices([
            'Default' => 'd',
            'Subscription' => 's',
            'License' => 'l'
        ]);
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
        yield NumberField::new('amount')->hideOnIndex();
        yield DateTimeField::new('paidOn', 'Date Paid');
        yield DateIntervalField::new('durationDateInterval', 'Package Duration')->hideOnIndex();
        yield TextField::new('staffMessage');
    }


    public function approveAction(AdminContext $context)
    {
        /**
         * @var CommerceInvoice $invoice
         */
        $invoice = $context->getEntity()->getInstance();

        $invoice->approve();

        return new RedirectResponse($this->generateUrl("app_dashboard_admin"));
    }
}
