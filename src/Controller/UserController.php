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
 * @Route("/manager")
 */
class UserController extends AbstractController
{
    const NEW_ADMIN_ROLE=["ROLE_ADMIN", "ROLE_MANAGER"];
    const NEW_MANAGER_ROLE=["ROLE_MANAGER"];
    const DEFAULT_ACTIVATION=false;
    const DEFAULT_PASSWORD="";

    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/manager", name="app_manager")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function newManager(Request $request, EntityManagerInterface $em): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActivated(self::DEFAULT_ACTIVATION);
            $user->setPassword(self::DEFAULT_PASSWORD);
            $user->setRoles(self::NEW_MANAGER_ROLE);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('user/new_manager.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin", name="app_admin")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function newAdmin(Request $request, EntityManagerInterface $em): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActivated(self::DEFAULT_ACTIVATION);
            $user->setPassword(self::DEFAULT_PASSWORD);
            $user->setRoles(self::NEW_ADMIN_ROLE);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('user/new_admin.html.twig', ['form' => $form->createView()]);
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
