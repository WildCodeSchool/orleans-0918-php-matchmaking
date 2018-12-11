<?php

namespace App\Repository;

use App\Entity\StatusEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StatusEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusEvent[]    findAll()
 * @method StatusEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StatusEvent::class);
    }

    // /**
    //  * @return StatusEvent[] Returns an array of StatusEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatusEvent
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
