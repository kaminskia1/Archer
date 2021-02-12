<?php

namespace App\Controller\Admin;

use App\Entity\CommercePackage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommercePackageCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommercePackageCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Packages')
            ->setEntityLabelInPlural('Packages')
            ->setEntityLabelInSingular(
                fn (?CommercePackage $gateway, string $pageName = "0") => $gateway ? ("Package: " . $gateway->getName() ): 'Package'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
        ;
    }

    public static function getEntityFqcn(): string
    {
        return CommercePackage::class;
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
        yield TextField::new('packageUserRole')->hideOnIndex();
        yield NumberField::new('stock');
        yield AssociationField::new('CommercePackageGroup');
        yield CollectionField::new('durationToPrice')->setHelp("Elements should be entered as \"Duration:Price\", one element per line")->hideOnIndex();
        yield CollectionField::new('customJSON', 'Custom JSON')->hideOnIndex();
        yield BooleanField::new('isEnabled', 'Enabled?');
        yield BooleanField::new('isVisible', 'Visible?');
        yield TextEditorField::new('storeDescription');
        yield TextField::new('staffMessage');
    }
}
