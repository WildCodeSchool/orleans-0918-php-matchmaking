<?php

namespace App\Service;

use App\Entity\FormatEvent;
use App\Entity\RoundEvent;
use App\Entity\TableEvent;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;

/**
 * Class FormatEventManagement
 * @package App\Service
 */
class FormatEventManagement
{
    private $em;
    private $csvReader;

    /**
     * FormatEventManagement constructor.
     * @param EntityManagerInterface $entityManager
     * @param CsvReader $csvReader
     */
    public function __construct(EntityManagerInterface $entityManager, CsvReader $csvReader)
    {
        $this->em = $entityManager;
        $this->csvReader = $csvReader;
    }

    /**
     * @param array $datasets
     * @return string
     */
    public function addFormatEvent(array $datasets): string
    {
        $result = '';

        $resultImportCsv = $this->importCsvFile($datasets);
        if (empty($resultImportCsv['errors'])) {
            // check if format already exists
            if (is_null($this->checkFormatEventExists($resultImportCsv['numberOfTables']))) {
                $this->addFormatEventInDataBase($datasets['name'], $resultImportCsv['numberOfTables']);
                $lastFormatEvent = $this->getLastEvent();
                $this->checkNumberOfTableInDataBase($resultImportCsv['numberOfTables']);
                // Add Round Format Event
                foreach ($resultImportCsv['rounds'] as $round) {
                    $tableEvent = $this->getTableEventDetails($round['table_number']);

                    for ($i = 1; $i < count($round) - 1; $i++) {
                        $roundEvent = new RoundEvent();
                        $roundEvent->setFormatEvent($lastFormatEvent);
                        $roundEvent->setTableEvent($tableEvent[0]);
                        $roundEvent->setSpeechRound($round['speechRound']);
                        $roundEvent->setUserSpeech($round['userSpeech']);
                        $this->em->persist($roundEvent);
                        $this->em->flush();
                    }
                }
            } else {
                $result = 'Ce format existe déjà dans la base.';
            }
        } else {
            $result = $resultImportCsv['errors'];
        }

        return $result;
    }

    /**
     * @param int $tableNumber
     * @return array
     */
    private function getTableEventDetails(int $tableNumber): array
    {
        return $this->em
            ->getRepository(TableEvent::class)
            ->createQueryBuilder('t')
            ->where('t.name LIKE :number')
            ->setParameter('number', '%' . $tableNumber . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $numberOfTables
     * @return FormatEventManagement
     */
    private function checkNumberOfTableInDataBase(int $numberOfTables): FormatEventManagement
    {
        for ($i = 1; $i <= $numberOfTables; $i++) {
            $tableEventExist = $this->getTableEventDetails($i);

            if (empty($tableEventExist)) {
                // Add Table
                $tableEvent = new TableEvent();
                $tableEvent->setName('Table ' . $i);
                $this->em->persist($tableEvent);
                $this->em->flush();
            }
        }

        return $this;
    }

    /**
     * @return FormatEvent
     */
    private function getLastEvent(): FormatEvent
    {
        // Get Last event
        $lastFormatEvent = $this->em
            ->getRepository(FormatEvent::class)
            ->findOneBy([], ['id' => 'desc'], 1, 0);

        return $lastFormatEvent;
    }

    /**
     * @param string $name
     * @param int $numberOfTables
     * @return FormatEventManagement
     */
    private function addFormatEventInDataBase(string $name, int $numberOfTables): FormatEventManagement
    {
        // Add format event
        $formatEvent = new FormatEvent();
        $formatEvent->setName($name);
        $formatEvent->setNumberOfTables($numberOfTables);
        $this->em->persist($formatEvent);
        $this->em->flush();

        return $this;
    }

    /**
     * @param int $numberOfTables
     * @return FormatEvent|null
     */
    private function checkFormatEventExists(int $numberOfTables): ?FormatEvent
    {
        $formatEvent = $this->em
            ->getRepository(FormatEvent::class)
            ->findOneBy(['numberOfTables' => $numberOfTables]);

        return $formatEvent;
    }

    /**
     * @param array $datasets
     * @return array
     */
    private function importCsvFile(array $datasets): array
    {
        $importCsv = ['errors' => '', 'numberOfTables' => 0, 'rounds' => []];

        try {
            $records = $this->csvReader->read($datasets['csvFile']->getPathName());
            $numberOfTables = 0;

            foreach ($records as $record) {
                if ($record['Table'] != '') {
                    $numberOfTables++;
                }
                // Add Rounds
                for ($i = 1; $i < count($record) - 1; $i++) {
                    $importCsv['rounds'][] = [
                        'table_number' => $numberOfTables,
                        'speechRound' => $i,
                        'userSpeech' => $record['Round ' . $i]
                    ];
                }
            }
            $importCsv['numberOfTables'] = $numberOfTables;
        } catch (Exception $exception) {
            $importCsv['errors'] = $exception->getMessage();
        }

        return $importCsv;
    }
}
