<?php

namespace App\Controller\Admin;

use App\Entity\Core\CoreUser;
use Doctrine\DBAL\Types\ArrayType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Class CoreUserCrudController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CoreUserCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Users')
            ->setEntityLabelInSingular(
                fn (?CoreUser $user, string $pageName = "0") => $user ? ($user->getUuid() . ( $user->getNickname() != "" ? " (" . $user->getNickname() . ")" : "" ) ) : 'User'
            )
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle('index', 'Users')
            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->remove(CRUD::PAGE_INDEX, Action::DELETE);
        return $actions->add(Crud::PAGE_INDEX, Action::new('detail', 'View')->linkToCrudAction('detail'));
    }

    public static function getEntityFqcn(): string
    {
        return CoreUser::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('uuid', "User ID")->hideOnForm()->formatValue(function ($v){
            if ($this->getDoctrine()->getRepository(CoreUser::class)->findOneBy(['uuid' => $v])->getInfractionPoints() >= 500)
            {
                return "[INFRACTED] $v";
            }
            return $v;
        });
        yield TextField::new('nickname');
        yield DateTimeField::new('registrationDate')->hideOnForm();
        yield  TextField::new('plainPassword', "New Password")->onlyOnForms()->setRequired( $pageName == "new" );
        yield ChoiceField::new('roles')->allowMultipleChoices(true)->setChoices([
            "User" => "ROLE_USER",
            "Banned" => "ROLE_BANNED",
            "Subscriber" => "ROLE_SUBSCRIBER",
            "Admin" => "ROLE_ADMIN"
        ]);
        yield AssociationField::new('registrationCode')
            ->onlyWhenCreating()
            ->setRequired(true);
        yield TextField::new('hwid', 'Hardware ID')->hideOnIndex();
        yield NumberField::new('infractionPoints', 'Infraction Points')->hideOnIndex();
        yield ArrayField::new('infractionTypes', 'Infraction Types')->hideOnIndex();
        yield DateTimeField::new('lastWebsiteLoginDate', "Last Site Login")->hideOnForm();
        yield DateTimeField::new('lastLoaderLoginDate', "Last Loader Login")->hideOnForm();
        yield TextareaField::new('staffNote');










/*

        yield IdField::new('loggerCommandUserInfractions', "Internal ID")->onlyOnDetail();
        yield IdField::new('apiKey', "Internal ID")->onlyOnDetail();
        yield IdField::new('apiAesKey', "Internal ID")->onlyOnDetail();
        yield IdField::new('roles', "Internal ID")->onlyOnDetail();
        yield IdField::new('lastLoginDate', "Internal ID")->onlyOnDetail();
        yield IdField::new('lastLoaderIP', "Internal ID")->onlyOnDetail();
        yield IdField::new('uuid', "Internal ID")->onlyOnDetail();
        yield IdField::new('plainPassword', "Internal ID")->onlyOnDetail();
        yield IdField::new('password', "Internal ID")->onlyOnDetail();
        yield IdField::new('loaderIPCollection', "Internal ID")->onlyOnDetail();
        yield IdField::new('registrationDate', "Internal ID")->onlyOnDetail();
        yield IdField::new('nickname', "Internal ID")->onlyOnDetail();
        yield IdField::new('loggerCommandUserSubscriptions', "Internal ID")->onlyOnDetail();
        yield IdField::new('registrationCode', "Internal ID")->onlyOnDetail();
        yield IdField::new('hwid', "Internal ID")->onlyOnDetail();
        yield IdField::new('lastSiteLoginDate', "Internal ID")->onlyOnDetail();
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield IdField::new('staffNote', "Internal ID")->onlyOnDetail();
        yield IdField::new('CommerceUserSubscriptions', "Internal ID")->onlyOnDetail();
        yield IdField::new('CommercePurchase', "Internal ID")->onlyOnDetail();
        yield IdField::new('lastSiteIP', "Internal ID")->onlyOnDetail();
        yield IdField::new('infractionPoints', "Internal ID")->onlyOnDetail();
        yield IdField::new('CommerceInvoices', "Internal ID")->onlyOnDetail();
        yield IdField::new('lastLoaderLoginDate', "Internal ID")->onlyOnDetail();
        yield IdField::new('siteIPCollection', "Internal ID")->onlyOnDetail();
        yield IdField::new('loggerCommandAuths', "Internal ID")->onlyOnDetail();
        yield IdField::new('commerceTransactions', "Internal ID")->onlyOnDetail();
        yield IdField::new('backupCodes', "Internal ID")->onlyOnDetail();
        yield IdField::new('apiKeyExpiry', "Internal ID")->onlyOnDetail();
        yield IdField::new('infractionTypes', "Internal ID")->onlyOnDetail();
        yield IdField::new('apiAesIV', "Internal ID")->onlyOnDetail();
*/
    }











}