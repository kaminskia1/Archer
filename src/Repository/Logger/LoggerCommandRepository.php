<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerCommand[]    findAll()
 * @method LoggerCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerCommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerCommand::class);
    }

    // /**
    //  * @return LoggerCommand[] Returns an array of LoggerCommand objects
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
    public function findOneBySomeField($value): ?LoggerCommand
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
