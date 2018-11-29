<?php

namespace App\Repository;

use App\Entity\TableEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TableEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableEvent[]    findAll()
 * @method TableEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TableEvent::class);
    }

    // /**
    //  * @return TableEvent[] Returns an array of TableEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableEvent
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
