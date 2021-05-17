<?php

namespace App\Repository\Commerce;

use App\Entity\Commerce\CommerceInvoice;
use App\Model\CommerceTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommerceInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommerceInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommerceInvoice[]    findAll()
 * @method CommerceInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommerceInvoiceRepository extends ServiceEntityRepository
{

    use CommerceTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommerceInvoice::class);
    }

    // /**
    //  * @return CommerceInvoice[] Returns an array of CommerceInvoice objects
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
    public function findOneBySomeField($value): ?CommerceInvoice
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
