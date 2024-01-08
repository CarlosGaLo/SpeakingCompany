<?php

namespace App\Repository;

use App\Entity\MailingElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailingElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailingElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailingElement[]    findAll()
 * @method MailingElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailingElement::class);
    }

    // /**
    //  * @return MailingElement[] Returns an array of MailingElement objects
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
    public function findOneBySomeField($value): ?MailingElement
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
