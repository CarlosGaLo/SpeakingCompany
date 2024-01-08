<?php

namespace App\Repository;

use App\Entity\ModuleTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleTest[]    findAll()
 * @method ModuleTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleTest::class);
    }

    // /**
    //  * @return ModuleTest[] Returns an array of ModuleTest objects
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
    public function findOneBySomeField($value): ?ModuleTest
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
