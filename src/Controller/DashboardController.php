<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RoundEvent;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\StatusEvent;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/pause/{id}/{currentLap}", name="dashboard_pause",
     * options={"expose"=true}, requirements={"id"="\d+","currentLap"="\d+"})
     * @param Event $event
     * @param int $currentLap
     * @return Response
     */
    public function pause(Event $event, int $currentLap) : Response
    {
        if (!$this->checkAuthorizedUser($event)) {
            return $this->redirectToRoute('event_index');
        }

        if ($this->checkEventStatus($event)===false) {
            return $this->redirectToRoute('event_index');
        }

        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        $rounds = $this->getDoctrine()->getManager()->getRepository(RoundEvent::class)
            ->findBy(['formatEvent' => $event->getFormatEvent(), 'speechTurn' => $currentLap]);

        $players = [];
        foreach ($event->getPlayers() as $player) {
            $players[$player->getSpeakerNumber()] = [
               ucwords(strtolower($player->getFirstname())),
               strtoupper($player->getName())
            ];
        }

        return $this->render('dashboard/pause.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
            'rounds' => $rounds,
            'players' => $players
        ]);
    }

    /**
     * @Route("/run/{id}/{currentLap}", name="dashboard_run",
     * options={"expose"=true}, requirements={"id"="\d+","currentLap"="\d+"})
     * @param Event $event
     * @param int $currentLap
     * @return Response
     */
    public function run(Event $event, int $currentLap) : Response
    {
        if (!$this->checkAuthorizedUser($event)) {
            return $this->redirectToRoute('event_index');
        }

        if ($this->checkEventStatus($event)===false) {
            return $this->redirectToRoute('event_index');
        }

        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        return $this->render('dashboard/run.html.twig', [
            'event' => $event,
            'currentLap' => $currentLap,
            'maxLaps' => $maxLaps,
            'numberOfPlayers' => sqrt($event->getFormatEvent()->getNumberOfPlayers()),
        ]);
    }

    /**
     * @Route("/start/{id}", name="dashboard_start",
     * requirements={"id"="\d+"})
     * @param Event $event
     * @return Response
     */
    public function start(Event $event) : Response
    {
        if (!$this->checkAuthorizedUser($event)) {
            return $this->redirectToRoute('event_index');
        }

        if ($this->checkEventStatus($event)===false) {
            return $this->redirectToRoute('event_index');
        }

        $numberOfPlayers = $event->getFormatEvent()->getNumberOfPlayers();
        $maxLaps = sqrt($event->getFormatEvent()->getNumberOfPlayers())+1;

        $rounds = $this->getDoctrine()->getManager()->getRepository(RoundEvent::class)
            ->findBy(['formatEvent' => $event->getFormatEvent(), 'speechTurn' => 1]);

        $players = [];
        foreach ($event->getPlayers() as $player) {
            $players[$player->getSpeakerNumber()] = [
                ucwords(strtolower($player->getFirstname())),
                strtoupper($player->getName())
            ];
        }

        return $this->render('dashboard/start.html.twig', [
            'event' => $event,
            'numberOfPlayers' => $numberOfPlayers,
            'currentLap' => 1,
            'maxLaps' => $maxLaps,
            'rounds' => $rounds,
            'players' => $players
        ]);
    }

    /**
     * @Route("/end/{id}", name="dashboard_end",
     * requirements={"id"="\d+"})
     */
    public function end(Event $event)
    {
        if (!$this->checkAuthorizedUser($event)) {
            return $this->redirectToRoute('event_index');
        }

        if ($this->checkEventStatus($event)===false) {
            return $this->redirectToRoute('event_index');
        }

        $numberOfPlayers = $event->getFormatEvent()->getNumberOfPlayers();

        $status = $this->getDoctrine()->getmanager()
        ->getRepository(StatusEvent ::class)
        ->findOneBy(['state' => $event->getStatusEvent()-> getFinishState()], []);
        $event->setStatusEvent($status);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('dashboard/end.html.twig', [
            'event' => $event,
            'numberOfPlayers' => $numberOfPlayers,
            'society' => $event->getSociety(),
        ]);
    }

    /**
     * @param Event $event
     * @return bool
     */
    private function checkEventStatus(Event $event) : bool
    {
        if ($event->getStatusEvent()->getState()!=$event->getStatusEvent()->getInProgressState()) {
            $this->addFlash(
                'danger',
                'L\'accès au dashboard est réservé aux évènements en cours uniquement !'
            );
            return false;
        }
        return true;
    }

    /**
     * @param Event $event
     * @return bool
     */
    private function checkAuthorizedUser(Event $event) : bool
    {

        if (in_array("ROLE_MANAGER", $this->getUser()->getRoles())
        && $event->getSociety()->getId() !== $this->getUser()->getSociety()->getId()) {
            $this->addFlash(
                'danger',
                'Vous n\'avez pas accès à cet événement !'
            );
            return false;
        }
        return true;
    }
}
