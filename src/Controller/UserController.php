<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    const MANAGER_ROLE = ["ROLE_MANAGER"];
    const ADMIN_ROLE = ["ROLE_ADMIN"];
    const DEFAULT_ACTIVATION = false;
    const DEFAULT_PASSWORD = "";
    const DEFAULT_LENGTH_PASSWORD = 8;

    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/manager", name="manager_index", methods="GET|POST")
     */
    public function indexManager(UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl("update", ["role" => "manager"]),
            'method' => 'POST',
        ]);
        return $this->render('user/manager.html.twig', [
            'form' => $form->createView(),
            'users' => $userRepository->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC'])
        ]);
    }

    /**
     * @Route("/{userId}/{role}/update", requirements={"role": "manager|admin"}, name="update", methods="POST")
     * @param Request $request
     * @param UserRepository $userRepo
     * @param string $role
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param PasswordGenerator $passwordGenerator
     * @param int $userId
     * @return Response
     */
    public function update(
        Request $request,
        UserRepository $userRepo,
        string $role,
        UserPasswordEncoderInterface $passwordEncoder,
        PasswordGenerator $passwordGenerator,
        int $userId = 0
    ): Response {
        $user = new User();
        if ($userId > 0) {
            $user = $userRepo->find($userId);
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($userId == 0) {
                $user->setActivated(self::DEFAULT_ACTIVATION);
                $password = $passwordGenerator->generate(self::DEFAULT_LENGTH_PASSWORD);
                $user->setPassword($passwordEncoder->encodePassword($user, $password));

                if ($role == 'manager') {
                    $user->setRoles(self::MANAGER_ROLE);
                } else {
                    $user->setRoles(self::ADMIN_ROLE);
                }
            }
            $this->getDoctrine()->getManager()->persist($user);
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
        return $this->redirectToRoute($role . '_index');
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
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        $roles = $user->getRoles();

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre utilisateur a été supprimé !'
            );
        } else {
            $this->addFlash(
                'danger',
                "Votre utilisateur n\'a pas pu été supprimé !"
            );
        }

        if (in_array("ROLE_MANAGER", $roles)) {
            return $this->redirectToRoute('manager_index');
        }
    }
}
