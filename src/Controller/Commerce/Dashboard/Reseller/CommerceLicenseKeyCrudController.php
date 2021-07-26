<?php

namespace App\Controller\Commerce\Dashboard\Reseller;

use App\Admin\Field\DateIntervalField;
use App\Entity\Commerce\CommerceLicenseKey;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceInvoiceCrudController
 *
 * @package App\Controller\Commerce\Dashboard\Admin
 * @IsGranted("ROLE_SELLER")
 */
class CommerceLicenseKeyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommerceLicenseKey::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'License Keys')
            ->setEntityLabelInPlural('License Keys')
            ->setEntityLabelInSingular(
                fn(?CommerceLicenseKey $key, string $pageName = "0") => $key ? ("License Key: " .
                    $key->getCode()) : 'License Key'
            )
            ->setEntityPermission('ROLE_SELLER');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.purchasedBy = :val');
        $response->setParameter('val', $this->getUser());

        return $response;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission('detail', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('edit'))->setPermission('edit', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('delete'))->setPermission('delete', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_INDEX, Action::new('new'))->setPermission('new', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_DETAIL, Action::new('delete'))->setPermission('delete', 'ROLE_ADMIN')
            ->remove(Crud::PAGE_DETAIL, Action::new('edit'))->setPermission('edit', 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code', 'Code')->hideOnForm();
        yield DateIntervalField::new('duration')->hideOnForm();
        yield AssociationField::new('package')->setTemplateName('crud/field/text')->hideOnForm();
        yield AssociationField::new('invoice')->setTemplateName('crud/field/text')->hideOnForm();
        yield BooleanField::new('active', 'Active')->hideOnForm()
            ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false);
        yield AssociationField::new('usedBy', 'Redeemer')->setTemplateName('crud/field/text')->hideOnForm();
        yield DateTimeField::new('createdOn', 'Purchase Date')->hideOnForm();


    }
}