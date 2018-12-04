<?php

namespace App\Controller;

use App\Entity\FormatEvent;
use App\Entity\Timer;
use App\Form\TimerType;
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
     * @return Response
     */
    public function index(Request $request): Response
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
  
        // FormatEvents List
        $formatEvents = $this->getDoctrine()
            ->getRepository(FormatEvent::class)
            ->findBy([], ['numberOfPlayers' => 'ASC']);

        return $this->render('settings/index.html.twig', [
            'timer' => $timer,
            'formTimer' => $form->createView(),
            'formatEvents' => $formatEvents,
        ]);
    }
}
