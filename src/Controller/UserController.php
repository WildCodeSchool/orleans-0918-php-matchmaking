<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    const NEW_MANAGER_ROLE=["ROLE_MANAGER"];
    const DEFAULT_ACTIVATION=false;
    const DEFAULT_PASSWORD="";

    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/manager", name="app_manager", methods="GET|POST")
     */
    public function listManager(UserRepository $userRepository): Response
    {

        $form=$this->createForm(UserType::class, null, [
        'action' => $this->generateUrl('app_manager_update'),
        'method' => 'POST',
        ]);
        return $this->render('user/manager.html.twig', [
            'form' => $form->createView(),
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     * @Route("/manager/update", name="app_manager_update", methods="POST")
     */
    public function updateManager(Request $request, UserRepository $userRepository): Response
    {
        $form=$this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user->setActivated(self::DEFAULT_ACTIVATION);
            $user->setPassword(self::DEFAULT_PASSWORD);
            $user->setRoles(self::NEW_MANAGER_ROLE);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Votre utilisateur a été créé !'
            );
        } else {
            $this->addFlash(
                'error',
                'Les données que vous avez saisies ne sont pas valides.'
            );
        }

        return $this->redirectToRoute('app_manager');
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
