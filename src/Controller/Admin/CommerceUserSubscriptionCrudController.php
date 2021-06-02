<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceUserSubscription;
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
 * Class CommerceUserSubscriptionCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CommerceUserSubscriptionCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'User Subscriptions')
            ->setEntityLabelInPlural('User Subscriptions')
            ->setEntityLabelInSingular(
                fn (?CommerceUserSubscription $subscription, string $pageName = "0") => $subscription ? ("Subscription: " . $subscription->getId() ): 'Subscription'

            )
            ->setEntityPermission('ROLE_ADMIN')
            ->showEntityActionsAsDropdown()
            ;
    }

    public static function getEntityFqcn(): string
    {
        return CommerceUserSubscription::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield AssociationField::new('user')->setRequired(true);
        yield AssociationField::new('commercePackageAssoc', 'Package')->setRequired(true);
        yield DateTimeField::new('expiryDateTime', 'Expiration Date')->setRequired(true);
        yield BooleanField::new('active')
            ->hideOnForm()
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield TextField::new('staffMessage');
    }
}
