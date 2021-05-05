<?php

namespace App\Controller\Admin;

use App\Entity\Core\CoreModule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CoreModuleCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 *
 *
 * Layout
 *
 *  Controllers
 *  => Controller/{ModuleName}/{ModuleName}{ControllerName}Controller
 *  => Controller/{ModuleName}/{ModuleName}/Api/{ControllerName}Controller
 *  => Controller/{ModuleName}/{ModuleName}/Api/Secure/{ControllerName}Controller
 *
 *  Entity
 *  => Entity/{ModuleName}/{ModuleName}{EntityName}
 *
 *  Enum
 *  => Enum/{ModuleName}/{ModuleName}{EnumName}
 *
 *  EventListener
 *  => EventListener/{ModuleName}/{ModuleName}{EventName}Listener
 *
 *  EventSubscriber
 *  => EventSubscriber/{ModuleName}/{ModuleName}{SubscriberName}Subscriber
 *
 *  Form
 *  => Form/{ModuleName}{FormName}
 *
 *  Module
 *  => Module/{ModuleName}/
 *
 *  Repository
 *  => Repository/{ModuleName}/{ModuleName}{EntityName}
 *
 *  Template
 *  => {modulename}/{template}
 *
 */
class CoreModuleCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Modules')
            ->setEntityLabelInPlural('Modules')
            ->setEntityLabelInSingular(
                fn (?CoreModuleCrudController $module, string $pageName = "0") => $module ? ("Module: " . $module->getName() ): 'Module'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CoreModule::class;
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
        yield BooleanField::new('isEnabled', "Enabled?");
        yield ArrayField::new('customEntities', "Custom Entities")->hideOnIndex();
        yield ArrayField::new('customControllers', "Custom Controllers")->hideOnIndex();
    }
}
