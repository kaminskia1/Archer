<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommercePurchase;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercePurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercePurchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercePurchase[]    findAll()
 * @method CommercePurchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercePurchaseRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercePurchase::class);
    }

    // /**
    //  * @return CommercePurchase[] Returns an array of CommercePurchase objects
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
    public function findOneBySomeField($value): ?CommercePurchase
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
