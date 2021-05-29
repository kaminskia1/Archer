<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerCommandUserSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerCommandUserSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerCommandUserSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerCommandUserSubscription[]    findAll()
 * @method LoggerCommandUserSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerCommandUserSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerCommandUserSubscription::class);
    }

    // /**
    //  * @return LoggerCommandUserSubscription[] Returns an array of LoggerCommandUserSubscription objects
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
    public function findOneBySomeField($value): ?LoggerCommandUserSubscription
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
