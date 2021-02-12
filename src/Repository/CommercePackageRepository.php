<?php

namespace App\Repository;

use App\Entity\CommercePackage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercePackage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercePackage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercePackage[]    findAll()
 * @method CommercePackage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercePackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercePackage::class);
    }

    // /**
    //  * @return CommercePackage[] Returns an array of CommercePackage objects
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
    public function findOneBySomeField($value): ?CommercePackage
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
