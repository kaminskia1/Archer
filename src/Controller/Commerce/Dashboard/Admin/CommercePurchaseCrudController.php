<?php

namespace App\Controller\Commerce\Dashboard\Admin;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommercePurchase;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommercePurchaseCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommercePurchaseCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Purchases')
            ->setEntityLabelInPlural('Purchase')
            ->setEntityLabelInSingular(
                fn (?CommercePurchase $purchase, string $pageName = "0") => $purchase ? ("Purchase: " . $purchase->getId() ): 'Purchase'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CommercePurchase::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield AssociationField::new('user')->setRequired(true);
        yield AssociationField::new('commercePackage')->setRequired(true);
        yield AssociationField::new('commerceInvoice');
        yield AssociationField::new('commerceGatewayInstance', 'Gateway')->hideOnIndex();
        yield NumberField::new('amountPaid')
            ->setNumDecimals(2)
            ->setStoredAsString(false)
            ->formatValue(function ($value) {
                return $_ENV['COMMERCE_CURRENCY_SYMBOL'] . $value;
            })->setTextAlign('right');yield DateIntervalField::new('duration', 'Duration')->hideOnIndex();
        yield TextField::new('staffMessage');
    }
}
