<?php

namespace App\Repository;

use App\Entity\ModuleForumPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleForumPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleForumPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleForumPost[]    findAll()
 * @method ModuleForumPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleForumPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleForumPost::class);
    }

    // /**
    //  * @return ModuleForumPost[] Returns an array of ModuleForumPost objects
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
    public function findOneBySomeField($value): ?ModuleForumPost
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
