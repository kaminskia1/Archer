<?php

namespace App\Controller\Core\Dashboard\Admin;

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
 *
 * @package App\Controller\Core\Dashboard\Admin
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

        // Basic Info
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
        yield TextField::new('uuid', "UUID")->hideOnForm()->formatValue(function ($v){
            if ($this->getDoctrine()->getRepository(CoreUser::class)->findOneBy(['uuid' => $v])->getInfractionPoints() >= 500)
            {
                return "[INFRACTED] $v";
            }
            return $v;
        });
        yield TextField::new('nickname', "Nickname");
        yield AssociationField::new('groups', "Groups")->hideOnIndex()->setRequired(true);;
        yield TextField::new('plainPassword', "New Password")->onlyOnForms()->setRequired( $pageName == "new" );
        yield TextField::new('hwid', "Hardware ID")->hideOnIndex();
        yield DateTimeField::new('registrationDate', "Registration Date")->onlyOnDetail();

        // Infractions
        yield NumberField::new('infractionPoints', "Infraction Points")->hideOnIndex();
        yield ArrayField::new('infractionTypes', "Infraction Types")->hideOnIndex();

        // Other Relations
        yield AssociationField::new('registrationCode', "Registration Code")
            ->onlyWhenCreating()
            ->setRequired(true);;
        yield AssociationField::new('CommerceUserSubscriptions', "# of Subscriptions")->onlyOnDetail();
        yield AssociationField::new('CommercePurchases', "# of Purchases")->onlyOnDetail();
        yield AssociationField::new('CommerceInvoices', "# of Invoices")->onlyOnDetail();


        // Logs
        yield AssociationField::new('loggerCommandUserSubscriptions', "# of Loader Logins")->onlyOnDetail();
        yield DateTimeField::new('lastSiteLoginDate', "Last Site Login")->hideOnForm();
        yield DateTimeField::new('lastLoaderLoginDate', "Last Loader Login")->hideOnForm();
        yield TextField::new('lastSiteIP', "Last Site IP")->onlyOnDetail();
        yield ArrayField::new('siteIPCollection', "Site IP Collection")->onlyOnDetail();
        yield TextField::new('lastLoaderIP', "Last Loader IP")->onlyOnDetail();
        yield ArrayField::new('loaderIPCollection', "Loader IP Collection")->onlyOnDetail();
        yield TextField::new('staffNote', "Staff Note");

        /*
        // Unused / Unimplemented
        yield IdField::new('lastLoginDate', "Last Login")->hideOnForm();
        yield IdField::new('apiKeyExpiry', "API Key Expiry")->onlyOnDetail();
        yield IdField::new('apiKey', "API Key")->onlyOnDetail();
        yield IdField::new('apiAesKey', "API AES Key")->onlyOnDetail();
        yield IdField::new('apiAesIV', "API AES IV")->onlyOnDetail();
        yield IdField::new('backupCodes', "Account Backup Codes")->onlyOnDetail();
        */
    }











}