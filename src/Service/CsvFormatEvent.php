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
    protected function _validate(): bool
    {
        $nbLineInCsv = count($this->getDataset());
        $headerCsv = $this->getDataset()[0];
        $this->setCsvHeaderFormat(['Table', 'Player', 'Round']);

        // Check Header Csv
        if (!$this->checkHeaderCsv($headerCsv)) {
            throw new CsvException('L\'en-tête de votre fichier doit être : Table, Player, Round 1, Round 2, etc.');
        }

        return true;
    }

    /**
     * Import CSV in database
     */
    protected function _import(): void
    {
        // TODO: Implement _import() method.
    }

    /**
     * Check if header CSV is formatting like Table, Player, Round 1, Round 2 etc.
     * @param array $headerCsv
     * @return bool
     */
    private function checkHeaderCsv(array $headerCsv): bool
    {
        $result = true;

        if ($headerCsv[0] === $this->getCsvHeaderFormat()[0] && $headerCsv[1] === $this->getCsvHeaderFormat()[1]) {
            $round = 1;
            for ($i = 2; $i < count($headerCsv); $i++) {
                $validNameCell = $this->getCsvHeaderFormat()[2] . ' ' . $round;
                if ($headerCsv[$i] !== $validNameCell) {
                    $result = false;
                    break;
                }
                $round++;
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @var integer
     */
//    private $numberOfPlayers;
    /**
     * Defined a CSV header Format
     */
//    const CSV_HEADER_FORMAT = ;

    /**
     * @var string
     */
//    private $csvName;



    /**
     * Verify if CSV file is correctly formating
     * @return bool
     */
//    public function validate(): bool
//    {
//        parent::validate();
//
//        $csv = Reader::createFromPath($this->getPath());
//        $recordsIterator = $csv->getRecords();
//
//        $nbLineInCsv = iterator_count($recordsIterator);
//        $records = iterator_to_array($recordsIterator);
//        $headerCsv = $records[0];
//
//        $this->setDataset($records);
//
//
//
//        // Check if format not already exists and verify Grid Round
//        $nbRound = count($headerCsv) - 2;
//        $validGridRound = ($nbRound - 1) ** 2;
//        $this->setNumberOfPlayers($validGridRound);
//        if (!$this->checkFormatEventExists()) {
//            throw new CsvException('Ce format est déjà présent dans la base.');
//        }
//        if ($validGridRound !== ($nbLineInCsv - 1)) {
//            throw new CsvException('Votre matrice n\'est pas valide.');
//        }
//
//        // Check Number of Table
//        if (!$this->checkNumberOfTable($records, $nbRound - 1)) {
//            throw new CsvException('L\une des tables n\'a le nombre de participants requis.');
//        }
//
//        // Check speaker's cell is integer
//        if (!$this->checkSpeakerCellIsInteger($records)) {
//            throw new CsvException('Les participants doivent être identifiés par des entiers.');
//        }
//
//        // Check if the numbers of speakers are a sequence of numbers, between [1-nbSpeackers]
//        if (!$this->checkNumbersSpeakersIsASequence($records, $this->getNumberOfPlayers())) {
//            throw new CsvException('Votre matrice ne contient pas une suite logique d\'entiers pour l\'un des rounds.');
//        }
//
//        return $this->isState();
//    }

    /**
     * Test and Import format in database
     */
//    public function import(): void
//    {
//        $this->getEm()->getConnection()->beginTransaction();
//        try {
//            $this->addFormatEvent();
//            $this->checkDatabaseTableExists();
//            $this->addRoundEvent();
//            $this->getEm()->getConnection()->commit();
//        } catch (CsvException $csvException) {
//            $this->getEm()->getConnection()->rollBack();
//            throw new CsvException('Erreur lors de l\'import dans la base de données.');
//        }
//    }

    /**
     * Add round event in database
     */
//    private function addRoundEvent() : void
//    {
//        $records = $this->getDataset();
//        $lastEventFormat = $this->getLastEvent();
//
//        for ($i = 1; $i < count($records); $i++) {
//            $tableEvent = $this->getTableEventDetails($records[$i][0]);
//            $round = 1;
//            for ($j = 2; $j < count($records[$i]); $j++) {
//                $roundEvent = new RoundEvent();
//                $roundEvent->setFormatEvent($lastEventFormat);
//                $roundEvent->setTableEvent($tableEvent[0]);
//                $roundEvent->setSpeechTurn($round);
//                $roundEvent->setSpeaker($records[$i][$j]);
//                $this->getEm()->persist($roundEvent);
//                $this->getEm()->flush();
//                $round++;
//            }
//        }
//    }

    /**
     * @return FormatEvent|object
     */
//    private function getLastEvent() : FormatEvent
//    {
//        // Get Last event
//        $lastFormatEvent = $this->getEm()
//            ->getRepository(FormatEvent::class)
//            ->findBy([], ['id' => 'desc'], 1, 0);
//
//        return $lastFormatEvent[0];
//    }

    /**
     * Add format event in database
     */
//    private function addFormatEvent() : void
//    {
//        $formatEvent = new FormatEvent();
//        $formatEvent->setName($this->getCsvName());
//        $formatEvent->setNumberOfPlayers($this->getNumberOfPlayers());
//        $this->getEm()->persist($formatEvent);
//        $this->getEm()->flush();
//    }

    /**
     * Check if table's name exists in database
     */
//    private function checkDatabaseTableExists() : void
//    {
//        $records = $this->getDataset();
//
//        for ($i = 1; $i < count($records); $i++) {
//            $tableExist = $this->getTableEventDetails(strtoupper($records[$i][0]));
//            if (empty($tableExist)) {
//                // Add Table Event
//                $tableEvent = new TableEvent();
//                $tableEvent->setName('TABLE' . strtoupper($records[$i][0]));
//                $this->getEm()->persist($tableEvent);
//                $this->getEm()->flush();
//            }
//        }
//    }
//
//    /**
//     * @param string $name
//     * @return array
//     */
//    private function getTableEventDetails(string $name) : array
//    {
//        return $this->getEm()
//            ->getRepository(TableEvent::class)
//            ->createQueryBuilder('t')
//            ->where('t.name LIKE :name')
//            ->setParameter('name', '%' . strtoupper($name) . '%')
//            ->getQuery()
//            ->getResult();
//    }
//
//    /**
//     * Check if the numbers of speakers are a sequence of numbers, between [1-$nbSpeackers]
//     * @param array $records
//     * @param int $nbSpeackers
//     * @return bool
//     */
//    private function checkNumbersSpeakersIsASequence(array $records, int $nbSpeackers): bool
//    {
//        $result = true;
//
//        $speackersSequence = range(1, $nbSpeackers);
//        $checkNumbersSequences = [];
//
//        for ($i = 1; $i < count($records); $i++) {
//            $j = 1;
//            for ($y = 2; $y < count($records[$i]); $y++) {
//                $checkNumbersSequences[$j][] = $records[$i][$y];
//                $j++;
//            }
//        }
//
//        foreach ($checkNumbersSequences as $checkNumbersSequence) {
//            $checkNumbersSequence = array_unique($checkNumbersSequence);
//            if (!empty(array_diff($speackersSequence, $checkNumbersSequence))) {
//                $result = false;
//            }
//        }
//
//        return $result;
//    }
//
//    /**
//     * Check if speaker's cell is an integer
//     * @param array $records
//     * @return bool
//     */
//    private function checkSpeakerCellIsInteger(array $records): bool
//    {
//        $result = true;
//
//        for ($i = 1; $i < count($records); $i++) {
//            for ($y = 1; $y < count($records[$i]) - 1; $y++) {
//                if (!preg_match('/^[1-9]+/', $records[$i][$y])) {
//                    $result = false;
//                    break;
//                }
//            }
//        }
//
//        return $result;
//    }
//
//    /**
//     * Check if the number of tables equals the number of rounds-1
//     * @param array $records
//     * @param int $nbRound
//     * @return bool
//     */
//    private function checkNumberOfTable(array $records, int $nbRound): bool
//    {
//        $result = true;
//        $tableNames = [];
//
//        for ($i = 1; $i < count($records); $i++) {
//            $tableNames[] = $records[$i][0];
//        }
//
//        $tableNames = array_unique($tableNames);
//
//        if (count($tableNames) !== $nbRound) {
//            $result = false;
//        }
//
//        return $result;
//    }
//
//    /**
//     * Check if the format event already exists
//     * @return bool
//     */
//    public function checkFormatEventExists() : bool
//    {
//        $formatEvent = $this->getEm()
//            ->getRepository(FormatEvent::class)
//            ->findBy(['numberOfPlayers' => $this->getNumberOfPlayers()], [], 1, 0);
//
//        return empty($formatEvent);
//    }
//

//
//    /**
//     * @return string
//     */
//    public function getCsvName(): string
//    {
//        return $this->csvName;
//    }
//
//    /**
//     * @param string $csvName
//     * @return CsvFormatEvent
//     */
//    public function setCsvName(string $csvName): CsvFormatEvent
//    {
//        $this->csvName = $csvName;
//        return $this;
//    }
//
//    /**
//     * @return int
//     */
//    public function getNumberOfPlayers(): int
//    {
//        return $this->numberOfPlayers;
//    }
//
//    /**
//     * @param int $numberOfPlayers
//     * @return CsvFormatEvent
//     */
//    public function setNumberOfPlayers(int $numberOfPlayers): CsvFormatEvent
//    {
//        $this->numberOfPlayers = $numberOfPlayers;
//        return $this;
//    }
}
