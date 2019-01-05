<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RoundEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/pause/{id}/{currentLap}", name="dashboard_pause",  requirements={"id"="\d+","lap"="\d+"})
     * @param Event $event
     * @param int $currentLap
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pause(Event $event, int $currentLap) : Response
    {
        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        $rounds = $this->getDoctrine()->getManager()->getRepository(RoundEvent::class)
            ->findBy(['formatEvent' => $event->getFormatEvent(), 'speechTurn' => $currentLap]);

        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
            'rounds' => $rounds,
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
