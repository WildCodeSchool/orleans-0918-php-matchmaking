<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findPresentPlayer(Event $event): array
    {
        $query = $this->createQueryBuilder('e')
            ->join('e.players', 'p', 'WITH', 'e.id = :event_id')
            ->addSelect('p')
            ->where('p.isPresence = true')
            ->setParameter('event_id', $event->getId())
            ->getQuery();

        return $query->execute();
    }
}
