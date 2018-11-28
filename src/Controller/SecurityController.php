<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
            $user->setActivated(false);
            $user->setPassword("");
            $user->setRoles(["ROLE_MANAGER"]);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('security/new_manager.html.twig', ['form' => $form->createView()]);
    }
}
