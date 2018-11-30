<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use App\Entity\Timer;

class EventController extends AbstractController
{

    /**
     * @Route("manager/events", name="event_list")
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/list.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("admin/event/add", name="event_add")
     */
    public function add(Request $request): Response
    {
        $event = new Event();

        $timer = $this->getDoctrine()
            ->getRepository(Timer::class)
            ->findOneBy([], ['id' => 'desc'], 1, 0);

        $todayDate = new \DateTime();

        $event->setRoundMinutes($timer->getRoundMinutes());
        $event->setRoundSeconds($timer->getRoundSeconds());
        $event->setPauseMinutes($timer->getPauseMinutes());
        $event->setPauseSeconds($timer->getPauseSeconds());
        $event->setDate($todayDate);

        $form = $this->createForm(
            EventType::class,
            $event,
            ['method' => Request::METHOD_POST]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre événement à été ajouté !'
            );

            return $this->redirectToRoute('event_list');
        }

        return $this->render('event/add.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }

     /**
     * @Route("/manager/event/edit/{id}", name="event_edit", methods="GET|POST")
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre événement à bien été modifié !'
            );

            return $this->redirectToRoute('event_list');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'formEdit' => $form->createView(),
        ]);
    }
}
