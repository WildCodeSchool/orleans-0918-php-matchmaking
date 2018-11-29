<?php

namespace App\Service;

use App\Exception\CsvException;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class Csv implements CsvImportInterface, CsvValidatorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     */
    const CSV_HEADER_FORMAT = ['Table', 'Player', 'Round'];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Verify if CSV file is correctly formating
     * @return bool
     */
    public function validate(): bool
    {
        if (!file_exists($this->getPath())) {
            throw new \LogicException('File does not exist !');
        }

        if (!in_array(mime_content_type($this->getPath()), ['text/csv', 'text/plain'])) {
            throw new \LogicException('File format is not valid !');
        }

        $csv = Reader::createFromPath($this->getPath());
        $recordsIterator = $csv->getRecords();

        $nbLineInCsv = iterator_count($recordsIterator);
        $records = iterator_to_array($recordsIterator);
        $headerCsv = $records[0];

        // Check Header Csv
        if (!$this->checkHeaderCsv($headerCsv)) {
            throw new CsvException('L\'en-tête de votre fichier doit être : Table, Player, Round 1, Round 2, etc.');
        }

        // Check Grid Round
        $nbRound = count($headerCsv) - 2;
        $validGridRound = $nbSpeackers = ($nbRound - 1) ** 2;
        if ($validGridRound !== ($nbLineInCsv - 1)) {
            throw new CsvException('Votre matrice n\'est pas valide.');
        }

        // Check Number of Table
        if (!$this->checkNumberOfTable($records, $nbRound - 1)) {
            throw new CsvException('L\une des tables n\'a le nombre de participants requis.');
        }

        // Check speaker's cell is integer
        if (!$this->checkSpeakerCellIsInteger($records)) {
            throw new CsvException('Les participants doivent être identifiés par des entiers.');
        }

        // Check if the numbers of speakers are a sequence of numbers, between [1-$nbSpeackers]
        if (!$this->checkNumbersSpeakersIsASequence($records, $nbSpeackers)) {
            throw new CsvException('Votre matrice ne contient pas une suite logique d\'entiers pour l\'un des rounds.');
        }

        return true;
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
        $result = true;
        $tableNames = [];

        for ($i = 1; $i < count($records); $i++) {
            $tableNames[] = $records[$i][0];
        }

        $tableNames = array_unique($tableNames);

        if (count($tableNames) !== $nbRound) {
            $result = false;
        }

        return $result;
    }

    /**
     * Check if header CSV is formatting like Table, Player, Round 1, Round 2 etc.
     * @param array $headerCsv
     * @return bool
     */
    private function checkHeaderCsv(array $headerCsv): bool
    {
        $result = true;

        if ($headerCsv[0] === self::CSV_HEADER_FORMAT[0] && $headerCsv[1] === self::CSV_HEADER_FORMAT[1]) {
            $round = 1;
            for ($i = 2; $i < count($headerCsv); $i++) {
                $validNameCell = self::CSV_HEADER_FORMAT[2] . ' ' . $round;
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
     *
     */
    public function import(): void
    {
        // TODO: Implement import() method.
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
     * @return Csv
     */
    public function setEm(EntityManagerInterface $em): Csv
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Csv
     */
    public function setPath(string $path): Csv
    {
        $this->path = $path;
        return $this;
    }
}
