<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerCommandUserInfraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerCommandUserInfraction|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerCommandUserInfraction|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerCommandUserInfraction[]    findAll()
 * @method LoggerCommandUserInfraction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerCommandUserInfractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerCommandUserInfraction::class);
    }

    // /**
    //  * @return LoggerCommandUserInfraction[] Returns an array of LoggerCommandUserInfraction objects
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
    public function findOneBySomeField($value): ?LoggerCommandUserInfraction
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
