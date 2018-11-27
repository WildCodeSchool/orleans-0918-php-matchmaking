<?php

namespace App\Service;

use App\Entity\FormatEvent;
use App\Entity\RoundEvent;
use App\Entity\TableEvent;
use Doctrine\ORM\EntityManager;
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
     * @param EntityManager $entityManager
     * @param CsvReader $csvReader
     */
    public function __construct(EntityManager $entityManager, CsvReader $csvReader)
    {
        $this->em = $entityManager;
        $this->csvReader = $csvReader;
    }

    /**
     * Check if format event already exist
     * @param int $numberOfTables
     * @return array
     */
    public function checkFormatEventAlreadyExist(int $numberOfTables): array
    {
        $formatEvent = $this->em
            ->getRepository(FormatEvent::class)
            ->findBy(['numberOfTables' => $numberOfTables]);

        return $formatEvent;
    }

    /**
     * @param array $datasets
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addFormatEvent(array $datasets) : string
    {
        // Add format Event and get the last event
        $lastFormatEvent = $this->addFormatEventAndGetLastEvent($datasets);
        // Check if number of tables in db is OK
        $this->checkNumberOfTableInDB($datasets['numberOfTables']);
        // Import CSV File
        $resultImport = $this->importFormatEventCsvFile($datasets, $lastFormatEvent);

        return $resultImport;
    }

    /**
     * Add format event and get the last Event
     * @param array $datasets
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addFormatEventAndGetLastEvent(array $datasets): array
    {
        // Add format event
        $formatEvent = new FormatEvent();
        $formatEvent->setName($datasets['name']);
        $formatEvent->setNumberOfTables($datasets['numberOfTables']);
        $this->em->persist($formatEvent);
        $this->em->flush();

        // Get Last Id event
        $lastFormatEvent = $this->em
            ->getRepository(FormatEvent::class)
            ->findBy([], ['id' => 'desc'], 1, 0);

        return $lastFormatEvent;
    }

    /**
     * Check if the number of Tables it's ok in database
     * @param int $numberOfTables
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function checkNumberOfTableInDB(int $numberOfTables)
    {
        for ($i = 1; $i <= $numberOfTables; $i++) {
            $tableEventExist = $this->getTableDetails($i);

            if (empty($tableEventExist)) {
                // Add Table
                $tableEvent = new TableEvent();
                $tableEvent->setName('Table ' . $i);
                $this->em->persist($tableEvent);
                $this->em->flush();
            }
        }
    }

    /**
     * @param int $tableNumber
     * @return array
     */
    private function getTableDetails(int $tableNumber): array
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
     * Import CSV and Insert values in database
     * @param array $dataset
     * @param array $formatEvent
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function importFormatEventCsvFile(array $datasets, array $formatEvent): string
    {
        $resultImport = '';

        try {
            $records = $this->csvReader->read($datasets['csvFile']->getPathName());
            $tableRound = 1;
            $tableEvent = null;

            foreach ($records as $record) {
                if ($record['Table'] != '') {
                    // Get Table Id
                    $tableEvent = $this->getTableDetails($tableRound);
                    $tableRound++;
                }

                // Add Rounds in Database
                for ($i = 1; $i < count($record) - 1; $i++) {
                    $roundEvent = new RoundEvent();
                    $roundEvent->setFormatEvent($formatEvent[0]);
                    $roundEvent->setTableEvent($tableEvent[0]);
                    $roundEvent->setSpeechRound($i);
                    $roundEvent->setUserSpeech($record['Round ' . $i]);
                    $this->em->persist($roundEvent);
                    $this->em->flush();
                }
            }
        } catch (Exception $exception) {
            $resultImport = $exception->getMessage();
        }

        return $resultImport;
    }
}
