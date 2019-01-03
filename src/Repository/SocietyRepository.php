<?php

namespace App\Repository;

use App\Entity\Society;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Society|null find($id, $lockMode = null, $lockVersion = null)
 * @method Society|null findOneBy(array $criteria, array $orderBy = null)
 * @method Society[]    findAll()
 * @method Society[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocietyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Society::class);
    }
}
