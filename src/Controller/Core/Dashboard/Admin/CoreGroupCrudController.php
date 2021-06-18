<?php

namespace App\Controller\Core\Dashboard\Admin;

use App\Entity\Core\CoreGroup;
use App\Entity\Core\CoreModule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CoreGroupCrudController
 *
 * @package App\Controller\Core\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CoreGroupCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Groups')
            ->setEntityLabelInPlural('Groups')
            ->setEntityLabelInSingular(
                fn (?CoreGroup $group, string $pageName = "0") => $group ? ("Group: " . $group->getName() ): 'Group'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CoreGroup::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('name', "Name");
        yield TextField::new('internalName', "Internal Name");
        yield AssociationField::new('users', "Users");
        yield AssociationField::new('inherits', "Inherits")->hideOnIndex();
        yield NumberField::new('priority', 'Priority')->hideOnIndex();
        yield ColorField::new('color', "Color");
        yield ArrayField::new('permissions', "Permissions")->hideOnIndex();
    }
}
