<?php

namespace App\Service;

use App\Entity\FormatEvent;
use App\Entity\RoundEvent;
use App\Entity\TableEvent;
use App\Exception\CsvException;
use Doctrine\ORM\EntityManagerInterface;

class CsvFormatEvent extends Csv
{
    /**
     * @var integer
     */
    private $numberOfPlayers;

    /**
     * @var array
     */
    private $formatEventInDB = [];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvFormatEvent constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return bool
     */
    protected function __validate(): bool
    {
        $nbLineInCsv = count($this->getDataset());
        $headerCsv = $this->getDataset()[0];
        $this->setCsvHeaderFormat(['Table', 'Player', 'Round']);

        // Check Header Csv
        if (!$this->checkHeaderCsv($headerCsv)) {
            throw new CsvException('L\'en-tête de votre fichier doit être : Table, Player, Round 1, Round 2, etc.');
        }

        $nbRound = count($headerCsv) - 2;
        $this->setNumberOfPlayers(($nbRound - 1) ** 2);

        // Check if the format does not already exist
        if ($this->checkFormatExist($this->getNumberOfPlayers())) {
            throw new CsvException('Ce format est déjà enregistré.');
        }

        if ($this->getNumberOfPlayers() !== ($nbLineInCsv - 1)) {
            throw new CsvException('Votre matrice n\'est pas valide.');
        }

        // Check Number of table
        if (!$this->checkNumberOfTable($this->getDataset(), $nbRound - 1)) {
            throw new CsvException('Le nombre de table n\'est pas valide.');
        }

        // Check speaker's cell is integer
        if (!$this->checkSpeakerCellIsInteger($this->getDataset())) {
            throw new CsvException('Les participants doivent être identifiés par des entiers.');
        }

        // Check if the numbers of speakers are a sequence of numbers, between [1-nbSpeackers]
        if (!$this->checkNumbersSpeakersIsASequence($this->getDataset(), $this->getNumberOfPlayers())) {
            throw new CsvException('Votre matrice ne contient pas une suite logique d\'entiers pour l\'un des rounds.');
        }

        return true;
    }

    /**
     * Import CSV in database
     */
    protected function __import(): void
    {
        $this->getEm()->getConnection()->beginTransaction();

        try {
            $formatEvent = $this->addFormatEvent();
            $formatEvent = $this->addRoundEvent($formatEvent);
            $this->getEm()->persist($formatEvent);
            $this->getEm()->flush();
            $this->getEm()->getConnection()->commit();
        } catch (\Exception $e) {
            $this->getEm()->getConnection()->rollBack();
        }
    }

    /**
     * @param FormatEvent $formatEvent
     * @return FormatEvent
     */
    private function addRoundEvent(FormatEvent $formatEvent) : FormatEvent
    {
        for ($i = 1; $i < count($this->getDataset()); $i++) {
            $round = 1;
            $tableEvent = $this->getTableEvent($this->getDataset()[$i][0]);
            for ($j = 2; $j < count($this->getDataset()[$i]); $j++) {
                $roundEvent = new RoundEvent();
                $roundEvent->setFormatEvent($formatEvent);
                $roundEvent->setTableEvent($tableEvent);
                $roundEvent->setSpeechTurn($round);
                $roundEvent->setSpeaker($this->getDataset()[$i][$j]);
                $formatEvent->addRoundEvent($roundEvent);
                $round++;
            }
        }

        return $formatEvent;
    }

    /**
     * @param int $numberOfPlayers
     * @return bool
     */
    private function checkFormatExist(int $numberOfPlayers): bool
    {
        return in_array($numberOfPlayers, $this->getFormatEventInDB());
    }

    /**
     * @param string $name
     * @return TableEvent
     */
    private function getTableEvent(string $name) : TableEvent
    {
        $tableEvent = $this->getEm()
            ->getRepository(TableEvent::class)
            ->findOneBy(['name' => $name], []);

        // Add Table, if not exists
        if (empty($tableEvent)) {
            $tableEvent = new TableEvent();
            $tableEvent->setName(strtoupper($name));
            $this->getEm()->persist($tableEvent);
            $this->getEm()->flush();
        }

        return $tableEvent;
    }

    /**
     * Add format event in database
     */
    private function addFormatEvent() : FormatEvent
    {
        $formatEvent = new FormatEvent();
        $formatEvent->setName($this->getName());
        $formatEvent->setNumberOfPlayers($this->getNumberOfPlayers());

        return $formatEvent;
    }

    /**
     * Check if the numbers of speakers are a sequence of numbers, between [1-$nbSpeackers]
     * @param array $records
     * @param int $nbSpeackers
     * @return bool
     */
    private function checkNumbersSpeakersIsASequence(array $records, int $nbSpeackers): bool
    {
        $result = true;

        $speackersSequence = range(1, $nbSpeackers);
        $checkNumbersSequences = [];

        for ($i = 1; $i < count($records); $i++) {
            $j = 1;
            for ($y = 2; $y < count($records[$i]); $y++) {
                $checkNumbersSequences[$j][] = $records[$i][$y];
                $j++;
            }
        }

        foreach ($checkNumbersSequences as $checkNumbersSequence) {
            $checkNumbersSequence = array_unique($checkNumbersSequence);
            if (!empty(array_diff($speackersSequence, $checkNumbersSequence))) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Check if speaker's cell is an integer
     * @param array $records
     * @return bool
     */
    private function checkSpeakerCellIsInteger(array $records): bool
    {
        $result = true;

        for ($i = 1; $i < count($records); $i++) {
            for ($y = 1; $y < count($records[$i]) - 1; $y++) {
                if (!preg_match('/^[1-9]+/', $records[$i][$y])) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Check if the number of tables equals the number of rounds-1
     * @param array $records
     * @param int $nbRound
     * @return bool
     */
    private function checkNumberOfTable(array $records, int $nbRound): bool
    {
        $tableNames = [];

        for ($i = 1; $i < count($records); $i++) {
            $tableNames[] = $records[$i][0];
        }

        $tableNames = array_unique($tableNames);

        (count($tableNames) !== $nbRound) ? $result = false : $result = true;

        return $result;
    }

    /**
     * Check if header CSV is formatting like Table, Player, Round 1, Round 2 etc.
     * @param array $headerCsv
     * @return bool
     */
    private function checkHeaderCsv(array $headerCsv): bool
    {
        $result = false;

        if ($headerCsv[0] === $this->getCsvHeaderFormat()[0] && $headerCsv[1] === $this->getCsvHeaderFormat()[1]) {
            $result = true;
            $round = 1;
            for ($i = 2; $i < count($headerCsv); $i++) {
                $validNameCell = $this->getCsvHeaderFormat()[2] . ' ' . $round;
                if ($headerCsv[$i] !== $validNameCell) {
                    $result = false;
                    break;
                }
                $round++;
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getNumberOfPlayers(): int
    {
        return $this->numberOfPlayers;
    }

    /**
     * @param int $numberOfPlayers
     * @return CsvFormatEvent
     */
    public function setNumberOfPlayers(int $numberOfPlayers): CsvFormatEvent
    {
        $this->numberOfPlayers = $numberOfPlayers;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormatEventInDB(): array
    {
        return $this->formatEventInDB;
    }

    /**
     * @param array $formatEventInDB
     * @return CsvFormatEvent
     */
    public function setFormatEventInDB(array $formatEventInDB): CsvFormatEvent
    {
        $this->formatEventInDB = $formatEventInDB;
        return $this;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param EntityManagerInterface $em
     * @return CsvFormatEvent
     */
    public function setEm(EntityManagerInterface $em): CsvFormatEvent
    {
        $this->em = $em;
        return $this;
    }
}
