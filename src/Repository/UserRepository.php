<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $role
     * @return array
     */
    public function findByRole($role): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->orderBy('u.lastName', 'ASC')
            ->getQuery();
        return $qb->execute();
    }

    /**
     * @param $role
     * @param $society
     * @return array
     */
    public function findBySocietyAndRole($society, $role): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.society = :society')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->setParameter('society', $society)
            ->orderBy('u.lastName', 'ASC')
            ->getQuery();
        return $qb->execute();
    }
}
