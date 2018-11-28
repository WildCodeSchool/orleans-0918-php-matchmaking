<?php

namespace App\Controller;

use App\Entity\FormatEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function index(): Response
    {
        // FormatEvents List
        $formatEvents = $this->getDoctrine()
            ->getRepository(FormatEvent::class)
            ->findBy([], ['numberOfPlayers' => 'ASC']);

        return $this->render('settings/index.html.twig', [
            'formatEvents' => $formatEvents
        ]);
    }
}
