<?php

namespace App\Repository\Core;

use App\Entity\Core\CoreModule;
use App\Model\CoreTraitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoreModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreModule[]    findAll()
 * @method CoreModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreModuleRepository extends ServiceEntityRepository
{

    use CoreTraitModel;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoreModule::class);
    }

    public function isModuleLoaded($value): bool
    {
        $res = $this->createQueryBuilder('c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();

        if ($res != null)
        {
            return $res->getIsEnabled();
        }
        return false;
    }
}
