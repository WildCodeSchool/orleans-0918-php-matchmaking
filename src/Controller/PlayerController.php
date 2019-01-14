<?php
/**
 * Created by PhpStorm.
 * User: vince
 * Date: 30/11/18
 * Time: 10:46
 */

namespace App\Controller;

use App\Entity\Player;
use App\Entity\StatusEvent;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;

class PlayerController extends AbstractController
{

    /**
     * @Route("/manager/player/{id}", name="player", requirements={"id"="\d+"}, methods="GET|POST")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function addPlayer(Request $request, Event $event): Response
    {

        if (in_array("ROLE_MANAGER", $this->getUser()->getRoles())
        && $event->getSociety()->getId() !== $this->getUser()->getSociety()->getId()) {
            $this->addFlash(
                'danger',
                'Vous n\'avez pas accès à cet événement !'
            );
            return $this->redirectToRoute('event_index');
        }

        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->addEvent($event);
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            // check if number of players max is reached
            if (count($event->getPlayers()) === $event->getFormatEvent()->getNumberOfPlayers()) {
                // change event's status to full status
                $statutEvent = $em->getRepository(StatusEvent::class)
                    ->findOneBy(['state' => $event->getStatusEvent()->getFullState()], []);

                $event->setStatusEvent($statutEvent);
                $em->flush();
            }

            $this->addFlash(
                'success',
                'Votre participant a été ajouté.'
            );

            return $this->redirectToRoute('player', ['id' => $event->getId()]);
        }

        $formEdit = $this->createForm(PlayerType::class, null, [
            'action' => $this->generateUrl("edit_player", ['id' => $event->getId(), 'playerId' => 0]),
            'method' => 'POST',
        ]);

        return $this->render('player/index.html.twig', [
            'players' => $event->getPlayers(),
            'form' => $form->createView(),
            'formEdit' => $formEdit->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/manager/player/{id}/edit/{playerId}", name="edit_player",
     *     requirements={"id"="\d+", "playerId"="\d+"}, methods="GET|POST")
     * @param Request $request
     * @param Event $event
     * @param Int $playerId
     * @param PlayerRepository $playerRepository
     * @return Response
     */
    public function editPlayer(
        Request $request,
        Event $event,
        PlayerRepository $playerRepository,
        int $playerId
    ): Response {
        $player = $playerRepository->find($playerId);
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
            $this->addFlash(
                'success',
                'Votre participant a été édité.'
            );
        }

        return $this->redirectToRoute('player', ['id' => $event->getId()]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @Route("/manager/player/data/{id}", name="player_edit", requirements={"id"="\d+"}, methods="POST")
     * @return Response
     */
    public function getEditData(Request $request, int $id): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $response = new Response();
            $response->setStatusCode(500);
            return $response;
        }
        $entityManager = $this->getDoctrine()->getManager();
        $player = $entityManager->getRepository(Player::class)->find($id);
        if (!$player) {
            throw $this->createNotFoundException(
                'Aucun participant n\'a été trouvé pour cet identifiant'
            );
        }
        $data=[];
        $data[]=$player->getName();
        $data[]=$player->getFirstname();
        $data[]=$player->getPhoneNumber();
        $data[]=$player->getMail();
        return $this->json($data);
    }

    /**
     * @param int $id
     * @param $presence
     * @param Request $request
     * @Route("/manager/player/{id}/{presence}", name="player_presence", requirements={"id"="\d+"}, methods="POST")
     * @return Response
     */
    public function updatePresence(int $id, $presence, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $player = $entityManager->getRepository(Player::class)->find($id);
            if (!$player) {
                throw $this->createNotFoundException(
                    'Aucun participant n\'a été trouvé pour cet identifiant'
                );
            }
            $player->setIsPresence($presence);
            $entityManager->flush();
            if ($presence == 0) {
                $message = "Ce participant n'est pas présent!";
            } else {
                $message = "Ce participant est bien présent!";
            }
            return $this->json($message);
        } else {
            return $this->json("Erreur");
        }
    }

    /**
     * @Route("/manager/player/{id}/delete/{player}", name="player_delete",
     *     requirements={"id"="\d+", "player_id"="\d+"}, methods="DELETE")
     * @param Request $request
     * @param Event $event
     * @param Player $player
     * @return Response
     */
    public function delete(Request $request, Event $event, Player $player): Response
    {
        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();

            // if event's status is full and the number of players is lower than number of players format
            if (count($event->getPlayers()) < $event->getFormatEvent()->getNumberOfPlayers()) {
                // change event's status to registration status
                $statutEvent = $em->getRepository(StatusEvent::class)
                    ->findOneBy(['state' => $event->getStatusEvent()->getRegistrationState()], []);

                $event->setStatusEvent($statutEvent);
                $em->flush();
            }

            $this->addFlash(
                'success',
                'Votre participant a été supprimé !'
            );
        }

        return $this->redirectToRoute('player', ['id' => $event->getId()]);
    }
}
