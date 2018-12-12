<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard/{id}", name="dashboard")
     */
    public function index(Event $event)
    {
        //$event = $this->getDoctrine()->getmanager()->getRepository(Event::class);
        return $this->render('dashboard/index.html.twig', [
            'event' => $event,
        ]);
    }
}
