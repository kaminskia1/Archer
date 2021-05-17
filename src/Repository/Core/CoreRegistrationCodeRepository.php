<?php

namespace App\Repository\Core;

use App\Entity\Core\CoreRegistrationCode;
use App\Model\CoreTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoreRegistrationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreRegistrationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreRegistrationCode[]    findAll()
 * @method CoreRegistrationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreRegistrationCodeRepository extends ServiceEntityRepository
{

    use CoreTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoreRegistrationCode::class);
    }

    // /**
    //  * @return CoreRegistrationCode[] Returns an array of CoreRegistrationCode objects
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

    public function findByCodeEnabled($value): ?CoreRegistrationCode
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
    public funcWhetion findOneBySomeField($value): ?CoreRegistrationCode
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
