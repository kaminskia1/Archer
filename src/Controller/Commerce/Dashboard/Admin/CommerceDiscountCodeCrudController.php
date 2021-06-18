<?php

namespace App\Controller\Commerce\Dashboard\Admin;

use App\Entity\Commerce\CommerceDiscountCode;
use App\Enum\Commerce\CommerceDiscountCodeTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceDiscountCodeCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceDiscountCodeCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return CommerceDiscountCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Discount Codes')
            ->setEntityLabelInSingular(
                fn(?CommerceDiscountCode $code, string $pageName = "0") => $code ? ("Code: [" . $code->getCode()) .
                    "]" : 'Discount Code'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Discount Codes')
            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code')->setRequired(false);
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield BooleanField::new('isEnabled', "Enabled?");
        yield ChoiceField::new('type')->allowMultipleChoices(false)->setChoices([
            "Amount" => CommerceDiscountCodeTypeEnum::TYPE_AMOUNT,
            "Percent" => CommerceDiscountCodeTypeEnum::TYPE_PERCENTAGE,
        ]);
        yield NumberField::new('amount')->hideOnIndex();
        yield NumberField::new('currentUsage')->onlyOnDetail();
        yield NumberField::new('maxUsage')->hideOnIndex();
        yield DateTimeField::new('expiryDate', 'Expires');
        yield TextField::new('staffMessage');
    }
}
