<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $manager;
    const REDIRECTION_ROUTE = 'homepage';

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/users", name="user_list")
     *  @IsGranted("ROLE_ADMIN", message="Cette page est réservée aux admistrateurs")
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->encode($user, $user->getPassword());
            $this->manager->setRole($form, $user);
            $this->manager->saveAndFlash($user, "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute(self::REDIRECTION_ROUTE);
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->encode($user, $user->getPassword());
            $this->manager->saveAndFlash($user, "L'utilisateur a bien été modifié");

            return $this->redirectToRoute(self::REDIRECTION_ROUTE);
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
