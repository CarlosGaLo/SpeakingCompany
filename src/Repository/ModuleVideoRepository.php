<?php

namespace App\Repository;

use App\Entity\ModuleVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleVideo[]    findAll()
 * @method ModuleVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleVideo::class);
    }

    // /**
    //  * @return ModuleVideo[] Returns an array of ModuleVideo objects
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
    public function findOneBySomeField($value): ?ModuleVideo
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
