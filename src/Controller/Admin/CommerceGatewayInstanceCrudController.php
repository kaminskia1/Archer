<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\CommerceGatewayType;
use App\Entity\Commerce\CommerceGatewayInstance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceGatewayInstanceCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceGatewayInstanceCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Payment Gateways')
            ->setEntityLabelInPlural('Payment Gateways')
            ->setEntityLabelInSingular(
                fn (?CommerceGatewayInstance $gateway, string $pageName = "0") => $gateway ? ("Gateway: " . $gateway->getName() ): 'Payment Gateway'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown();
    }

    public static function getEntityFqcn(): string
    {
        return CommerceGatewayInstance::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "ID")->hideOnForm();
        yield TextField::new('name');
        yield AssociationField::new('commerceGatewayType', 'Type')->setRequired(true);
        yield TextEditorField::new('description', 'Description');
        yield ArrayField::new('commerceGatewayTypeSettings', 'Gateway Settings');
        yield BooleanField::new('isActive', 'Active')->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
    }
}
