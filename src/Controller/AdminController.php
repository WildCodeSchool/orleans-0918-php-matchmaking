<?php
namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/new_admin", name="admin_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('admin_new');
        }
        return $this->render('admin/index.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }
}