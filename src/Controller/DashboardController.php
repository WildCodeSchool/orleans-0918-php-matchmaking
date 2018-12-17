<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/pause/{id}", name="dashboard_pause")
     */
    public function pause(Event $event)
    {
        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/run/{id}", name="dashboard_run")
     */
    public function run(Event $event)
    {
        return $this->render('dashboard/run.html.twig', [
            'event' => $event,
        ]);
    }
}
