<?php

namespace App\Controller;

use App\Entity\Society;
use App\Form\SocietyType;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/society")
 */
class SocietyController extends AbstractController
{
    /**
     * @param SocietyRepository $SocietyRepository
     * @return Response
     * @Route("/", name="society_index", methods="GET|POST")
     */
    public function index(SocietyRepository $societyRepository): Response
    {
        $form=$this->createForm(SocietyType::class, null, [
            'method' => 'POST',
        ]);

        $societies=$societyRepository->findAll();

        return $this->render('society/index.html.twig', [
            'form' => $form->createView(),
            'societies' => $societies
        ]);
    }

    /**
     * @Route("/{id}", name="society_delete", methods="DELETE")
     */
    public function delete(Request $request, Society $society): Response
    {
        if ($this->isCsrfTokenValid('delete'.$society->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($society);
            $em->flush();
        }

        return $this->redirectToRoute('society_index');
    }

    /**
     * @Route("/{id}/update", name="update_society", methods="POST")
     * @param Request $request
     * @param Society $society
     * @return Response
     */
    public function update(
        Request $request,
        Society $society = null
    ): Response {
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $society = $form->getData();

            $this->getDoctrine()->getManager()->persist($society);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Données sauvegardées !'
            );
        } else {
            $errors = "";
            foreach ($form->getErrors(true) as $error) {
                $errors .= ' ' . $error->getMessage();
            }
            $this->addFlash(
                'danger',
                'Erreur. ' . $errors
            );
        }
        return $this->redirectToRoute('society_index');
    }
}
