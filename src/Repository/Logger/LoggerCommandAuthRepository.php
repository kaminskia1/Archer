<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerCommandAuth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerCommandAuth|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerCommandAuth|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerCommandAuth[]    findAll()
 * @method LoggerCommandAuth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerCommandAuthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerCommandAuth::class);
    }

    // /**
    //  * @return LoggerCommandAuth[] Returns an array of LoggerCommandAuth objects
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
    public function findOneBySomeField($value): ?LoggerCommandAuth
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
