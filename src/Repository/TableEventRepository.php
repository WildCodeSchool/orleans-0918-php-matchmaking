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
}
