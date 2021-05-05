<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\CommerceGatewayType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceGatewayTypeCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceGatewayTypeCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Payment Gateway Types')
            ->setEntityLabelInPlural('Payment Gateway Types')
            ->setEntityLabelInSingular(
                fn (?CommerceGatewayType $gateway, string $pageName = "0") => $gateway ? ("Gateway Type: " . $gateway->getName() ): 'Payment Gateway'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown();
        ;
    }

    public static function getEntityFqcn(): string
    {
        return CommerceGatewayType::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "ID")->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('class');
    }
}
