<?php
namespace App\DataFixtures;

use App\Service\CsvFormatEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FormatEventFixtures extends Fixture
{
    private $path;

    public function __construct(string $projectDir)
    {
        $this->path = $projectDir;
    }

    public function load(ObjectManager $manager)
    {
        $csvFormatEvent = new csvFormatEvent($manager);
        $csvFormatEvent->setName('9 players');
        $csvFormatEvent->setPath($this->getPath() . '/src/DataFixtures/data/9-players.csv');
        $csvFormatEvent->validate();
        $csvFormatEvent->import();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
