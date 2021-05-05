<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommerceGatewayInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceGatewayInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceGatewayInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceGatewayInstance[]    findAll()
 * @method CommerceGatewayInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceGatwayInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceGatewayInstance::class);
    }

    // /**
    //  * @return CommerceGatwayInstance[] Returns an array of CommerceGatwayInstance objects
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
    public function findOneBySomeField($value): ?CommerceGatwayInstance
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
