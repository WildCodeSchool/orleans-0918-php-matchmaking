<?php

namespace App\Repository;

use App\Entity\Timer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Timer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timer[]    findAll()
 * @method Timer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Timer::class);
    }
}
