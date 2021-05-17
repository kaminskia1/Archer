<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommercePackageGroup;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercePackageGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercePackageGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercePackageGroup[]    findAll()
 * @method CommercePackageGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercePackageGroupRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercePackageGroup::class);
    }

    // /**
    //  * @return CommercePackageGroup[] Returns an array of CommercePackageGroup objects
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
    public function findOneBySomeField($value): ?CommercePackageGroup
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
