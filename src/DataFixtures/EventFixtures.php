<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\FormatEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Validator\Constraints\DateTime;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [FormatEventFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $date = new \DateTime();
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setTitle(ucfirst($faker->words(3, true)));
            $event->setDescription($faker->text(200));
            $event->setDate($faker->dateTimeBetween('-1 month', '+1 years'));
            $event->setPauseMinutes(rand(2, 5));
            $event->setPauseSeconds(rand(0, 59));
            $event->setRoundMinutes(rand(1, 2));
            $event->setRoundSeconds(rand(0, 59));
            $event->setUpdatedAt($date);
            $event->setLogo('defaultLogo.png');
            $lastFormatEvent = $manager->getRepository(FormatEvent::class)->findAll();
            $event->setFormatEvent($lastFormatEvent[0]);
            $manager->persist($event);
        }
        $manager->flush();
    }
}
