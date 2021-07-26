<?php

namespace App\Controller\Commerce\Dashboard\Admin;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommerceLicenseKey;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceInvoiceCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceLicenseKeyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommerceLicenseKey::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'License Keys')
            ->setEntityLabelInPlural('License Keys')
            ->setEntityLabelInSingular(
                fn(?CommerceLicenseKey $key, string $pageName = "0") => $key ? ("License Key: " .
                    $key->getCode()) : 'License Key'
            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('purchasedBy', 'Purchaser');
        yield TextField::new('code', 'Code')->hideOnForm();
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield DateIntervalField::new('duration')->onlyOnIndex();
        yield AssociationField::new('package')->setRequired(true);
        yield AssociationField::new('invoice');
        yield AssociationField::new('usedBy', 'Redeemer');
        yield DateIntervalField::new('duration')->setRequired(true)->hideOnIndex();
        yield BooleanField::new('active', 'Active')->onlyOnIndex()
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield BooleanField::new('active', 'Active')->hideOnIndex()
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);


    }
}