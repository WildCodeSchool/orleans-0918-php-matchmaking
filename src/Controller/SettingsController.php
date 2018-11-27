<?php

namespace App\Controller;

use App\Form\FormatEventType;
use App\Service\FormatEventManagement;
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
     * @param FormatEventManagement $formatEventManagement
     * @return Response
     */
    public function index(Request $request, FormatEventManagement $formatEventManagement): Response
    {
        // FormatEvent_Add
        $formAddFormatEvent = $this->createForm(FormatEventType::class);
        $validateAddFormatEvent = '';
        $resultImportCsv = '';

        $formAddFormatEvent->handleRequest($request);

        if ($formAddFormatEvent->isSubmitted() && $formAddFormatEvent->isValid()) {
            $datasets = $formAddFormatEvent->getData();
            $resultImportCsv = $formatEventManagement->addFormatEvent($datasets);
            if (empty($resultImportCsv)) {
                return $this->redirectToRoute('settings', ['status' => 'validateAddFormatEvent']);
            }
        }

        if (isset($_GET['status']) && $_GET['status'] === 'validateAddFormatEvent') {
            $validateAddFormatEvent = 'Vos modifications ont été enregistrées.';
        }

        return $this->render('settings/index.html.twig', [
            'formAddFormatEvent' => $formAddFormatEvent->createView(),
            'validateAddFormatEvent' => $validateAddFormatEvent,
            'errorAddFormatEvent' => $resultImportCsv
        ]);
    }
}
