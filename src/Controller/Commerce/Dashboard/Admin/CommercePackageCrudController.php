<?php

namespace App\Controller\Commerce\Dashboard\Admin;

use App\Entity\Commerce\CommercePackage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommercePackageCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
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
        Yield FormField::addPanel('Basic Information');
        yield TextField::new('name');
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('imageURI', 'Image File Name')->setHelp("File should previously be uploaded to public/uploads/")->hideOnIndex();
        yield AssociationField::new('CommercePackageGroup', 'Package Group')->setRequired(true);
        yield AssociationField::new('packageUserGroup', 'Subscription User Group')->hideOnIndex();
        yield NumberField::new('stock');
        yield TextEditorField::new('storeDescription');
        yield BooleanField::new('isEnabled', 'Enabled?')->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield BooleanField::new('isVisible', 'Visible?')->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield CollectionField::new('durationToPrice')->setHelp("Elements should be entered as \"Duration:Price\", one element per line, e.g. \"30:9.99\"")->hideOnIndex();
        yield CollectionField::new('customJSON', 'Custom JSON')->setHelp('Saved as a JSON array, values can be anything but objects/arrays will be double-encoded')->hideOnIndex();

        yield FormField::addPanel('Licensing');
        yield BooleanField::new('isKeyEnabled', 'Enabled?')->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield CollectionField::new('keyDurationToPrice')->setHelp("Elements should be entered as \"Amount:Duration:Price\", one element per line, e.g. \"1:30:9.99\"")->hideOnIndex();


    }
}
