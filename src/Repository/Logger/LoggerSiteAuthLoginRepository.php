<?php

namespace App\Repository\Logger;

use App\Entity\Logger\LoggerSiteAuthLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoggerSiteAuthLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoggerSiteAuthLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoggerSiteAuthLogin[]    findAll()
 * @method LoggerSiteAuthLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoggerSiteAuthLoginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoggerSiteAuthLogin::class);
    }

    // /**
    //  * @return LoggerSiteAuthLogin[] Returns an array of LoggerSiteAuthLogin objects
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
    public function findOneBySomeField($value): ?LoggerSiteAuthLogin
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
