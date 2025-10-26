<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('joueur/login.html.twig', ['error' => $error] );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout ()
    {

    }


    /**
     * @Route("/profile", name="profile_session")
     */
    public function afficherProfil ()
    {
        return $this->render('joueur/profil.html.twig');

    }
    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function editProfile(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profile_session', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/editprof.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }



}
