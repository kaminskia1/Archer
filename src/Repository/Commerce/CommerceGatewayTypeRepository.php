<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommerceGatewayType;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceGatewayType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceGatewayType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceGatewayType[]    findAll()
 * @method CommerceGatewayType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceGatewayTypeRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceGatewayType::class);
    }

    // /**
    //  * @return CommerceGatewayType[] Returns an array of CommerceGatewayType objects
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
    public function findOneBySomeField($value): ?CommerceGatewayType
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
