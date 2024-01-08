<?php

namespace App\Repository;

use App\Entity\WebsiteParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebsiteParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteParameter[]    findAll()
 * @method WebsiteParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteParameter::class);
    }

    // /**
    //  * @return WebsiteParameter[] Returns an array of WebsiteParameter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebsiteParameter
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
