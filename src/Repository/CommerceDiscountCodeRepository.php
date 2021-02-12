<?php

namespace App\Repository;

use App\Entity\CommerceDiscountCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceDiscountCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceDiscountCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceDiscountCode[]    findAll()
 * @method CommerceDiscountCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceDiscountCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceDiscountCode::class);
    }

    // /**
    //  * @return CommerceDiscountCode[] Returns an array of CommerceDiscountCode objects
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
    public function findOneBySomeField($value): ?CommerceDiscountCode
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
