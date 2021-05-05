<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommercePackageGroupCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommercePackageGroupCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Package Groups')
            ->setEntityLabelInPlural('Package Group')
            ->setEntityLabelInSingular(
                fn (?CommercePackageGroup $gateway, string $pageName = "0") => $gateway ? ("Group: " . $gateway->getName() ): 'Group'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CommercePackageGroup::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('imageURI', 'Image File Name')->setHelp("File should previously be uploaded to public/uploads/")->hideOnIndex();
        yield AssociationField::new('commercePackage', 'Group Packages')->hideOnForm();
        yield TextField::new('staffMessage');
    }
}
