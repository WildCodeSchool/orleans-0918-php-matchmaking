<?php

namespace App\Repository;

use App\Entity\RoundEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoundEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoundEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoundEvent[]    findAll()
 * @method RoundEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoundEvent::class);
    }
}
