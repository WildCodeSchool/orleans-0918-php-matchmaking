<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\FormatEvent;
use App\Entity\Society;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class SocietyFixtures extends Fixture
{
    const NAME = [
        'WCS',
        'PROJECT I/O',
        'APSIDE'
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 3; $i++) {
            $society = new Society();
            $society->setName(self::NAME[$i]);
            $manager->persist($society);
            $this->addReference('society_' . $i, $society);
        }
        $manager->flush();
    }
}
