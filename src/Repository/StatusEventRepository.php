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
}
