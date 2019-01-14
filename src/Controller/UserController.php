<?php

namespace App\Controller;

use App\Entity\Society;
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
     * @var string
     */
    private $adminEmail;

    /**
     * @var string
     */
    private $adminGlobalName;

    public function __construct(string $adminEmail, string $adminGlobalName)
    {
        $this->adminEmail = $adminEmail;
        $this->adminGlobalName = $adminGlobalName;
    }

    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     * @Route("/manager", name="manager_index", methods="GET|POST")
     */
    public function indexManager(UserRepository $userRepository, Request $request): Response
    {
        $form = $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl("update", ["role" => "manager"]),
            'method' => 'POST',
        ]);

        $roles=$this->getUser()->getRoles();
        $societyId="";

        if (in_array('ROLE_ADMIN', $roles)) {
            $users=$userRepository->findByRole(self::MANAGER_ROLE[0]);
        } elseif ((in_array('ROLE_MANAGER', $roles))) {
            $users=$userRepository->findBySocietyAndRole(
                $this->getUser()->getSociety()->getId(),
                self::MANAGER_ROLE[0]
            );
            $societyId = (string)$this->getUser()->getSociety()->getId();
        }

        return $this->render('user/manager.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'societyId' => $societyId
        ]);
    }

    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/admin", name="admin_index", methods="GET|POST")
     */
    public function indexAdmin(UserRepository $userRepository): Response
    {
        $form=$this->createForm(UserType::class, null, [
            'action' => $this->generateUrl("update", ["role" =>"admin"]),
            'method' => 'POST',
        ]);

        $users=$userRepository->findByRole(self::ADMIN_ROLE[0]);

        return $this->render('user/admin.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/{userId}/{role}/update", requirements={"role": "manager|admin"}, name="update", methods="POST")
     * @param Request $request
     * @param UserRepository $userRepo
     * @param string $role
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param PasswordGenerator $passwordGenerator
     * @param \Swift_Mailer $mailer
     * @param int $userId
     * @return Response
     */
    public function update(
        Request $request,
        UserRepository $userRepo,
        string $role,
        UserPasswordEncoderInterface $passwordEncoder,
        PasswordGenerator $passwordGenerator,
        \Swift_Mailer $mailer,
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

                $message = (new \Swift_Message('Match Making : Vos identifiants.'))
                    ->setFrom([$this->adminEmail => $this->adminGlobalName])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/registration.html.twig',
                            [
                                'lastname' => $user->getLastName(),
                                'firstname' => $user->getFirstName(),
                                'email' => $user->getEmail(),
                                'password' => $password
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);

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

        if (($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token')))
            && ($this->getUser()->getId() != $user->getId())) {
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
                "Vous ne pouvez pas vous supprimer !"
            );
        }

        if (in_array("ROLE_ADMIN", $roles)) {
            return $this->redirectToRoute('admin_index');
        } else {
            return $this->redirectToRoute('manager_index');
        }
    }

    /**
     * @param UserRepository $userRepository
     * @param Society $society
     * @return Response
     * @Route("/society/{id}/admin", name="society_users_list", methods="GET|POST")
     */
    public function societyUsersList(UserRepository $userRepository, Society $society): Response
    {
        $form = $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl("update", ["role" => "manager"]),
            'method' => 'POST',
        ]);

        $users=$userRepository->findBy(['society' => $society]);

        return $this->render('user/manager.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'society' => $society
        ]);
    }
}
