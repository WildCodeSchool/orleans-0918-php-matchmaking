<?php

namespace App\Controller;

use App\Entity\FormatEvent;
use App\Entity\Timer;
use App\Exception\CsvException;
use App\Form\FormatEventType;
use App\Form\TimerType;
use App\Service\CsvFormatEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param CsvFormatEvent $csvFormatEvent
     * @return Response
     */
    public function index(Request $request, CsvFormatEvent $csvFormatEvent): Response
    {
        $timer = $this->getDoctrine()
            ->getRepository(Timer::class)
            ->findOneBy([], ['id' => 'desc'], 1, 0);

        $form = $this->createForm(TimerType::class, $timer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Les Timers ont été mis à jour.'
            );
        }

        // Add FormatEvent
        $formAddFormatEvent = $this->createForm(FormatEventType::class);
        $formAddFormatEvent->handleRequest($request);

        if ($formAddFormatEvent->isSubmitted() && $formAddFormatEvent->isValid()) {
            $dataset = $formAddFormatEvent->getData();
            $csvFormatEvent->setName($dataset['name']);
            $csvFormatEvent->setPath($dataset['csvFile']->getPathName());

            try {
                $csvFormatEvent->validate();
                $csvFormatEvent->import();
                $this->addFlash(
                    'success',
                    'Le nouveau format a été ajouté.'
                );
            } catch (CsvException | \Exception $csvException) {
                $this->addFlash(
                    'danger',
                    $csvException->getMessage()
                );
            }

            return $this->redirectToRoute('settings');
        }

        // FormatEvents List
        $formatEvents = $this->getDoctrine()
            ->getRepository(FormatEvent::class)
            ->findBy([], ['numberOfPlayers' => 'ASC']);

        return $this->render('settings/index.html.twig', [
            'timer' => $timer,
            'formTimer' => $form->createView(),
            'formatEvents' => $formatEvents,
            'formAddFormatEvent' => $formAddFormatEvent->createView()
        ]);
    }

    /**
     * @Route("/settings/{id}", name="format_event_delete", methods="DELETE")
     * @param Request $request
     * @param FormatEvent $formatEvent
     * @return Response
     */
    public function delete(Request $request, FormatEvent $formatEvent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $formatEvent->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formatEvent);
            $em->flush();

            $this->addFlash(
                'success',
                'Le format a été supprimé !'
            );
        }

        return $this->redirectToRoute('settings');
    }
}
