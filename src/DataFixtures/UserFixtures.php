<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    const ROLES = [
        'ROLE_ADMIN',
        'ROLE_MANAGER'
    ];

    const DEFAULT_PASSWORD = '1234';
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [SocietyFixtures::class];
    }

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = strtolower($firstName . '.' . $lastName . '@' . $faker->safeEmailDomain());
            $email = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $email);

            $user = new User();
            $user->setEmail($email);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setActivated(1);
            $user->setRoles([self::ROLES[rand(0, 1)]]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                self::DEFAULT_PASSWORD
            ));
            $user->setSociety($this->getReference('society_' . rand(0, 2)));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
