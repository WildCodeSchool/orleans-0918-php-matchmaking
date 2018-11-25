<?php

namespace App\Controller;

use App\Entity\FormatEvent;
use App\Entity\RoundEvent;
use App\Entity\TableEvent;
use App\Form\FormatEventType;
use Doctrine\ORM\EntityManager;
use League\Csv\Exception;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * Class SettingsController
 * @package App\Controller
 */

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function index(Request $request) : Response
    {
        // FormatEvent_Add
        $formAddFormatEvent = $this->createForm(FormatEventType::class);
        $errorAddFormatEvent = '';
        $validateAddFormatEvent = '';

        $formAddFormatEvent->handleRequest($request);

        if ($formAddFormatEvent->isSubmitted() && $formAddFormatEvent->isValid()) {
            $dataset = $formAddFormatEvent->getData();
            // Check the format does not already exist
            $formatAlreadyExist = $this->checkFormatEventAlreadyExist($dataset['numberOfTables']);
            if (empty($formatAlreadyExist)) {
                $entityManager = $this->getDoctrine()->getManager();
                // Add format Event and get the last event
                $lastFormatEvent = $this->addFormatEventAndGetLastEvent($entityManager, $dataset);
                // Check if number of tables in db is OK
                $this->checkNumberOfTableInDB($entityManager, $dataset['numberOfTables']);
                // Import CSV File
                $resultImportCSV = $this->importFormatEventCsvFile($entityManager, $dataset, $lastFormatEvent);

                if (empty($resultImportCSV)) {
                    return $this->redirectToRoute('settings', ['status' => 'validateAddFormatEvent']);
                }
                else {
                    $errorAddFormatEvent = $resultImportCSV;
                }

            } else {
                $errorAddFormatEvent = 'Ce format existe déjà dans la base de données.';
            }
        }

        if (isset($_GET['status']) && $_GET['status'] === 'validateAddFormatEvent') {
            $validateAddFormatEvent = 'Vos modifications ont été enregistrées.';
        }

        return $this->render('settings/index.html.twig', [
            'formAddFormatEvent' => $formAddFormatEvent->createView(),
            'validateAddFormatEvent' => $validateAddFormatEvent,
            'errorAddFormatEvent' => $errorAddFormatEvent
        ]);
    }

    /**
     * Check if format event already exist
     * @param int $numberOfTables
     * @return array
     */
    private function checkFormatEventAlreadyExist(int $numberOfTables) : array
    {
        $formatEvent = $this->getDoctrine()
            ->getRepository(FormatEvent::class)
            ->findBy(['numberOfTables' => $numberOfTables]);

        return $formatEvent;
    }

    /**
     * Add format event and get the last Event
     * @param EntityManager $em
     * @param array $dataset
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
   private function addFormatEventAndGetLastEvent(EntityManager $em, array $dataset) : array
   {
      // Add format event
      $formatEvent = new FormatEvent();
      $formatEvent->setName($dataset['name']);
      $formatEvent->setNumberOfTables($dataset['numberOfTables']);
      $em->persist($formatEvent);
      $em->flush();

      // Get Last Id event
      $lastFormatEvent = $this->getDoctrine()
          ->getRepository(FormatEvent::class)
          ->findBy([], ['id' => 'desc'], 1, 0);

      return $lastFormatEvent;
   }

    /**
     * Check if the number of Tables it's ok in database
     * @param EntityManager $em
     * @param int $numberOfTables
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
   private function checkNumberOfTableInDB(EntityManager $em, int $numberOfTables)
   {
       for ($i = 1; $i <= $numberOfTables; $i++) {
           $tableEventExist = $this->getTableDetails($i);

           if (empty($tableEventExist)) {
               // Add Table
               $tableEvent = new TableEvent();
               $tableEvent->setName('Table ' . $i);
               $em->persist($tableEvent);
               $em->flush();
           }
       }
   }

    /**
     * Import CSV and Insert values in database
     * @param EntityManager $em
     * @param array $dataset
     * @param array $formatEvent
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
   private function importFormatEventCsvFile(EntityManager $em, array $dataset, array $formatEvent) : string
   {

       $resultImport = '';

        try {
            $csvFile = Reader::createFromPath($dataset['csvFile']->getPathName(), 'r');
            $csvFile->setHeaderOffset(0);
            $records = $csvFile->getRecords();

            $tableRound = 1;

            foreach ($records as $record) {
                if ($record['Table'] != '') {
                    // Get Table Id
                    $tableEvent = $this->getTableDetails($tableRound);
                    $tableRound++;
                }

                // Add Rounds in Database
                for ($i = 1; $i < count($record)-1; $i++) {
                    $roundEvent = new RoundEvent();
                    $roundEvent->setFormatEvent($formatEvent[0]);
                    $roundEvent->setTableEvent($tableEvent[0]);
                    $roundEvent->setSpeechRound($i);
                    $roundEvent->setUserSpeech($record['Round ' . $i]);
                    $em->persist($roundEvent);
                    $em->flush();
                }
            }
        }
        catch (Exception $exception) {
            $resultImport = $exception->getMessage();
        }

        return $resultImport;
   }

    /**
     * @param int $tableNumber
     * @return array
     */
   private function getTableDetails(int $tableNumber) : array
   {
       return $this->getDoctrine()
           ->getRepository(TableEvent::class)
           ->createQueryBuilder('t')
           ->where('t.name LIKE :number')
           ->setParameter('number', '%' . $tableNumber . '%')
           ->getQuery()
           ->getResult();
   }
}
