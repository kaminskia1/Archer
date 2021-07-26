<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommerceLicenseKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method CommerceLicenseKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceLicenseKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceLicenseKey[]    findAll()
 * @method CommerceLicenseKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceLicenseKeyRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceLicenseKey::class);
    }

    // /**
    //  * @return CommerceLicenseKey[] Returns an array of CommerceLicenseKey objects
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
    public function findOneBySomeField($value): ?CommerceLicenseKey
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
