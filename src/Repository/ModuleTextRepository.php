<?php

namespace App\Repository;

use App\Entity\ModuleText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleText|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleText|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleText[]    findAll()
 * @method ModuleText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleText::class);
    }

    // /**
    //  * @return ModuleText[] Returns an array of ModuleText objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModuleText
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
