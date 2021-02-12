<?php

namespace App\Repository;

use App\Entity\RegistrationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegistrationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistrationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistrationCode[]    findAll()
 * @method RegistrationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistrationCode::class);
    }

    // /**
    //  * @return RegistrationCode[] Returns an array of RegistrationCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByCodeEnabled($value): ?RegistrationCode
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.code = :val')
            ->andWhere('r.enabled = :state')
            ->setParameter('val', $value)
            ->setParameter('state', true)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public funcWhetion findOneBySomeField($value): ?RegistrationCode
    {
        return $this->createQueryBuilder('r')
            ->andre('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
