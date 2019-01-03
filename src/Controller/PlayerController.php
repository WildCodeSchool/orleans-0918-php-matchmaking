<?php
/**
 * Created by PhpStorm.
 * User: vince
 * Date: 30/11/18
 * Time: 10:46
 */

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;

class PlayerController extends AbstractController
{

    /**
     * @Route("/manager/player/{id}", name="player", requirements={"id"="\d+"}, methods="GET|POST")
     */
    public function addPlayer(Request $request, Event $event): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->addEvent($event);
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre participant a été ajouté.'
            );

            return $this->redirectToRoute('player', ['id' => $event->getId()]);
        }

        return $this->render('player/index.html.twig', [
            'players' => $event->getPlayers(),
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @param int $id
     * @param $presence
     * @return Response
     * @Route("/manager/player/{id}/{presence}", name="player_presence", requirements={"id"="\d+"}, methods="GET|POST")
     * @return Response
     */
    public function updatePresence(int $id, $presence): Response
    {
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
    }

    /**
     * @Route("/manager/player/delete/{id}", name="player_delete", requirements={"id"="\d+"}, methods="DELETE")
     */
    public function delete(Request $request, Player $player): Response
    {

        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            $event = $request->request->get('event_id');
            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();


            $this->addFlash(
                'success',
                'Votre participant a été supprimé !'
            );
        }

        return $this->redirectToRoute('player', ['id' => $event]);
    }
}
