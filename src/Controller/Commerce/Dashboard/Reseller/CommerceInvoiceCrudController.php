<?php

namespace App\Controller\Commerce\Dashboard\Reseller;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class CommerceInvoiceCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_SELLER")
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
            ->setEntityPermission('ROLE_SELLER');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission('detail', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('edit'))->setPermission('edit', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('delete'))->setPermission('delete', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('new'))->setPermission('new', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_DETAIL, Action::new('delete'))->setPermission('delete', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_DETAIL, Action::new('edit'))->setPermission('edit', 'ROLE_ADMIN');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.user = :val');
        $response->setParameter('val', $this->getUser());

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'Invoice ID')->hideOnForm();
        yield ChoiceField::new('paymentState', 'Invoice State')->setChoices(
            [
                'Open' => CommerceInvoicePaymentStateEnum::INVOICE_OPEN,
                'Pending' => CommerceInvoicePaymentStateEnum::INVOICE_PENDING,
                'Paid' => CommerceInvoicePaymentStateEnum::INVOICE_PAID,
                'Cancelled' => CommerceInvoicePaymentStateEnum::INVOICE_CANCELLED,
                'Expired' => CommerceInvoicePaymentStateEnum::INVOICE_EXPIRED
            ]
        )->hideOnForm();
        yield ChoiceField::new('type')->setChoices([
            'Default' => 'd',
            'Subscription' => 's',
            'License' => 'l'
        ])->hideOnForm();
        yield AssociationField::new('commercePackage', 'Package')->setTemplateName('crud/field/text')->hideOnForm();
        yield DateIntervalField::new('durationDateInterval', 'Duration')->hideOnForm();
        yield NumberField::new('price')
            ->setNumDecimals(2)
            ->setStoredAsString(false)
            ->formatValue(function ($value) {
                return $_ENV['COMMERCE_CURRENCY_SYMBOL'] . $value;
            })->setTextAlign('right')->hideOnForm();
        yield DateTimeField::new('paidOn', 'Date Paid')->onlyOnDetail();
    }


}
