<?php

namespace App\Controller\Admin;

use App\Entity\RegistrationCode;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class RegistrationCodeCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class RegistrationCodeCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Registration Codes')
            ->setEntityLabelInSingular(
                fn (?RegistrationCode $code, string $pageName = "0") => $code ? ("Code: " . $code->getCode() ) : 'Code'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Registration Codes')
            ->showEntityActionsAsDropdown();
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail')
        );
    }

    public static function getEntityFqcn(): string
    {
        return RegistrationCode::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->hideOnForm();
        yield TextField::new('code')->setRequired(false);
        yield BooleanField::new('enabled')->hideOnForm()->setFormTypeOption('disabled', true);
        yield AssociationField::new('usedBy')->hideOnForm();
        yield DatetimeField::new("usageDate")->hideOnForm();
    }
}
