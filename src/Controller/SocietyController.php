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
     * @Route("/new", name="society_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $society = new Society();
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($society);
            $em->flush();

            return $this->redirectToRoute('society_index');
        }

        return $this->render('society/new.html.twig', [
            'society' => $society,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="society_show", methods="GET")
     */
    public function show(Society $society): Response
    {
        return $this->render('society/show.html.twig', ['society' => $society]);
    }

    /**
     * @Route("/{id}/edit", name="society_edit", methods="GET|POST")
     */
    public function edit(Request $request, Society $society): Response
    {
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('society_index', ['id' => $society->getId()]);
        }

        return $this->render('society/edit.html.twig', [
            'society' => $society,
            'form' => $form->createView(),
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
