<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\FormatEvent;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/pause/{id}/{currentLap}", name="dashboard_pause",
     * options={"expose"=true}, requirements={"id"="\d+","currentLap"="\d+"})
     */
    public function pause(Event $event, int $currentLap)
    {
        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
        ]);
    }

    /**
     * @Route("/run/{id}/{currentLap}", name="dashboard_run",
     * options={"expose"=true}, requirements={"id"="\d+","currentLap"="\d+"})
     */
    public function run(Event $event, int $currentLap)
    {
        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        return $this->render('dashboard/run.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
        ]);
    }

    /**
     * @Route("/end/{id}", name="dashboard_end",
     * requirements={"id"="\d+"})
     */
    public function end(Event $event)
    {
        $numberOfPlayers = $event->getFormatEvent()->getNumberOfPlayers();

        return $this->render('dashboard/end.html.twig', [
            'event' => $event,
            'numberOfPlayers' => $numberOfPlayers,
            'society' => $event->getSociety(),
        ]);
    }
}
