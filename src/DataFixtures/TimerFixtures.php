<?php

namespace App\DataFixtures;

use App\Entity\Timer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TimerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $timer = new Timer();
        $timer->setPauseMinutes(5);
        $timer->setPauseSeconds(0);
        $timer->setRoundMinutes(1);
        $timer->setRoundSeconds(0);
        $manager->persist($timer);
        $manager->flush();
    }
}
