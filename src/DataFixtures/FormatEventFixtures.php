<?php

namespace App\DataFixtures;

use App\Entity\FormatEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FormatEventFixtures extends Fixture
{
    const NUMBER_OF_PLAYERS = [9, 16, 25, 49];

    public function load(ObjectManager $manager)
    {
        foreach (self::NUMBER_OF_PLAYERS as $key => $numberOfPlayers) {
            $formatEvent = new FormatEvent();
            $formatEvent->setName($numberOfPlayers . ' players');
            $formatEvent->setNumberOfPlayers($numberOfPlayers);
            $manager->persist($formatEvent);
            $this->addReference('formatEvent_' . $key, $formatEvent);
        }
        $manager->flush();
    }
}
