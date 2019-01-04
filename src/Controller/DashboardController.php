<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RoundEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\FormatEvent;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/pause/{id}/{currentLap}", name="dashboard_pause",  requirements={"id"="\d+","lap"="\d+"})
     */
    public function pause(Event $event, int $currentLap)
    {
        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        $rounds = $this->getDoctrine()->getManager()->getRepository(RoundEvent::class)
            ->findBy(['formatEvent' => $event->getFormatEvent(), 'speechTurn' => $currentLap]);

        dump($rounds);
        exit();

        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
        ]);
    }

    /**
     * @Route("/run/{id}/{currentLap}", name="dashboard_run", requirements={"id"="\d+","lap"="\d+"})
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
}
