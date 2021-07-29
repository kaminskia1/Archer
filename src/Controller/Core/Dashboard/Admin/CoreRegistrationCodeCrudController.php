<?php

namespace App\Controller\Core\Dashboard\Admin;

use App\Entity\Core\CoreUser;
use App\Entity\Core\CoreRegistrationCode;
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
 * Class CoreRegistrationCodeCrudController
 * @package App\Controller\Core\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CoreRegistrationCodeCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Registration Codes')
            ->setEntityLabelInSingular(
                fn (?CoreRegistrationCode $code, string $pageName = "0") => $code ? ("Code: " . $code->getCode() ) : 'Code'

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
        return CoreRegistrationCode::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->hideOnForm();
        yield TextField::new('code')->setRequired(false);
        yield AssociationField::new('usedBy')->hideOnForm();
        yield DatetimeField::new("usageDate")->hideOnForm();
        yield BooleanField::new('enabled')->hideOnForm()
            ->setFormTypeOption('disabled', true)
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield TextField::new('staffMessage');
    }
}
