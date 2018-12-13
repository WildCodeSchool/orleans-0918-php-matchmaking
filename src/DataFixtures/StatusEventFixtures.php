<?php

namespace App\DataFixtures;

use App\Entity\StatusEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StatusEventFixtures extends Fixture
{
    /**
     * List of event's status
     */
    const EVENT_STATUS = [
        [0, 'En préparation', 'secondary'],
        [1, 'Inscription', 'info'],
        [2, 'Complet', 'danger'],
        [3, 'En cours', 'success'],
        [4, 'Terminé', 'light']
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::EVENT_STATUS as $status) {
            $statusEvent = new StatusEvent();
            $statusEvent->setState($status[0]);
            $statusEvent->setName($status[1]);
            $statusEvent->setColor($status[2]);
            $manager->persist($statusEvent);
            $this->addReference('status_' . $status[0], $statusEvent);
        }

        $manager->flush();
    }
}
