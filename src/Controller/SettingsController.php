<?php

namespace App\Controller;

use App\Form\FormatEventType;
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
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Add FormatEvent
        $formAddFormatEvent = $this->createForm(FormatEventType::class);
        $formAddFormatEvent->handleRequest($request);

        if ($formAddFormatEvent->isSubmitted() && $formAddFormatEvent->isValid()) {

        }


        return $this->render('settings/index.html.twig', [
            'formAddFormatEvent' => $formAddFormatEvent->createView()
        ]);
    }
}
