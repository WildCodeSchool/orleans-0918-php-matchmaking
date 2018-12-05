<?php

namespace App\Controller;

use App\Exception\CsvException;
use App\Form\FormatEventType;
use App\Service\CsvFormatEvent;
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
     * @param Request $request
     * @param CsvFormatEvent $csvFormatEvent
     * @return Response
     */
    public function index(Request $request, CsvFormatEvent $csvFormatEvent): Response
    {
        // Add FormatEvent
        $formAddFormatEvent = $this->createForm(FormatEventType::class);
        $formAddFormatEvent->handleRequest($request);

        if ($formAddFormatEvent->isSubmitted() && $formAddFormatEvent->isValid()) {
            $dataset = $formAddFormatEvent->getData();
            $csvFormatEvent->setName($dataset['name']);
            $csvFormatEvent->setPath($dataset['csvFile']->getPathName());

            try {
                $csvFormatEvent->validate();
                $csvFormatEvent->import();
            } catch (CsvException $csvException) {
                $this->addFlash(
                    'danger',
                    $csvException->getMessage()
                );
                return $this->render('settings/index.html.twig', [
                    'formAddFormatEvent' => $formAddFormatEvent->createView()
                ]);
            }

            $this->addFlash(
                'success',
                'Le nouveau format a été ajouté.'
            );
        }

        return $this->render('settings/index.html.twig', [
            'formAddFormatEvent' => $formAddFormatEvent->createView()
        ]);
    }
}
