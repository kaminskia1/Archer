<?php

namespace App\Repository\Core;

use App\Entity\Core\CoreGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoreGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreGroup[]    findAll()
 * @method CoreGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoreGroup::class);
    }

    // /**
    //  * @return CoreGroup[] Returns an array of CoreGroup objects
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
    public function findOneBySomeField($value): ?CoreGroup
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
