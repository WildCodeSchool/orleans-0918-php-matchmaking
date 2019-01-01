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
     * @Route("/pause/{id}/{lap}", name="dashboard_pause",  requirements={"id"="\d+","lap"="\d+"})
     */
    public function pause(Event $event, int $lap)
    {
        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
            'lap' => $lap,
            /* TODO : recup le nmbr de tours */
        ]);
    }

    /**
     * @Route("/run/{id}/{lap}", name="dashboard_run", requirements={"id"="\d+","lap"="\d+"})
     */
    public function run(Event $event, int $lap)
    {
        return $this->render('dashboard/run.html.twig', [
            'event' => $event,
            'lap' => $lap,
            /* TODO : recup le nmbr de tours */
        ]);
    }
}
