<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerSiteRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerSiteRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerSiteRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerSiteRequest[]    findAll()
 * @method LoggerSiteRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerSiteRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerSiteRequest::class);
    }

    // /**
    //  * @return LoggerSiteRequest[] Returns an array of LoggerSiteRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LoggerSiteRequest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
