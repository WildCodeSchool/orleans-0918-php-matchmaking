<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\StatusEvent;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Timer;
use Knp\Component\Pager\PaginatorInterface;

class EventController extends AbstractController
{

    /**
     * @Route("manager/events", name="event_index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getmanager()->getRepository(Event::class);
        $events = $em->findBy([], ['date' => 'DESC']);

        $result = $paginator->paginate(
            $events,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return $this->render('event/index.html.twig', [
            'events' => $result
        ]);
    }

    /**
     * @Route("admin/event/add", name="event_add")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function add(Request $request): Response
    {
        $event = new Event();

        $timer = $this->getDoctrine()->getRepository(Timer::class)
            ->findOneBy([], ['id' => 'desc'], 1, 0);

        $statutEvent = $this->getDoctrine()->getRepository(StatusEvent::class)
            ->findOneBy([], [], 1, 0);

        $todayDate = new \DateTime();
        $logoPath = new File($this->getParameter('kernel.project_dir').'/public/images/logos/defaultLogo.png');

        $event->setRoundMinutes($timer->getRoundMinutes());
        $event->setRoundSeconds($timer->getRoundSeconds());
        $event->setPauseMinutes($timer->getPauseMinutes());
        $event->setPauseSeconds($timer->getPauseSeconds());
        $event->setLogoFile($logoPath);
        $event->setDate($todayDate);

        $form = $this->createForm(EventType::class, $event, [
            'method' => Request::METHOD_POST,
            'status' => $statutEvent->getInPreparationState(),
            'statusFullState' => $statutEvent->getFullState(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre événement a été ajouté !'
            );

             return $this->redirectToRoute('event_index');
        }

        return $this->render('event/add.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/event/edit/{id}", name="event_edit", methods="GET|POST")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event, [
            'status' => $event->getStatusEvent()->getState(),
            'statusFullState' => $event->getStatusEvent()->getFullState(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre événement a bien été modifié !'
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'formEdit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods="DELETE")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre événement à été supprimé !'
            );
        }

        return $this->redirectToRoute('event_index');
    }
}
