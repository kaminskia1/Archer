<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommerceTransaction;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceTransaction[]    findAll()
 * @method CommerceTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceTransactionRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceTransaction::class);
    }

    // /**
    //  * @return CommerceTransaction[] Returns an array of CommerceTransactions objects
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
    public function findOneBySomeField($value): ?CommerceTransaction
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
