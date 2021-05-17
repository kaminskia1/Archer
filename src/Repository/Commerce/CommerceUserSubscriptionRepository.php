<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceUserSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceUserSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceUserSubscription[]    findAll()
 * @method CommerceUserSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceUserSubscriptionRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceUserSubscription::class);
    }

    public function checkIfPreexisting(CommercePurchase $purchase ): bool
    {
        return count($this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter( 'user', $purchase->getUser() )
            ->andWhere('c.commercePackageAssoc = :package')
            ->setParameter('package', $purchase->getCommercePackage() )
            ->getQuery()
            ->getResult()) > 0;

    }

    public function getByPurchase(CommercePurchase $purchase )
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter( 'user', $purchase->getUser() )
            ->andWhere('c.commercePackageAssoc = :package')
            ->setParameter('package', $purchase->getCommercePackage() )
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];

    }
    // /**
    //  * @return CommerceUserSubscription[] Returns an array of CommerceUserSubscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommerceUserSubscription
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
