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
                fn (?CoreUser $user, string $pageName = "0") => $user ? ($user->getUuid() . ( $user->getNickname() != "" ? " (" . $user->getNickname() . ")" : "" ) ) : 'CoreUser'
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

        yield TextField::new('uuid', "User ID")->hideOnForm()->formatValue(function ($v){
            if ($this->getDoctrine()->getRepository(CoreUser::class)->findOneBy(['uuid' => $v])->getInfractionPoints() >= 500)
            {
                return "[INFRACTED] $v";
            }
            return $v;
        });
        yield IdField::new('id', "Internal ID")->onlyOnDetail();
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
        yield DateTimeField::new('lastLoaderLoginDate', "Last Loader Login")->onlyOnDetail();
        yield TextareaField::new('staffNote');
    }

}