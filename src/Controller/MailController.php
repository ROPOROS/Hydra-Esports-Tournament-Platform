<?php

namespace App\Controller;

use App\Form\MailType;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/mail")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/", name="app_mail" )
     */
    public function index(Request $request,\Swift_Mailer $mailer)
    {
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail=$form->getData();


            $message = (new \Swift_Message('Traitement'))
                ->setFrom('hydraesport20@gmail.com')
                ->setTo('raedchebbi8@gmail.com')
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'mail/mail.html.twig',
                        compact('mail')
                    ),
                    'text/html'
                )

                ;
            $mailer->send($message);

            $this->addFlash('message','la reponse a ete envoyer');

//            return $this->redirectToRoute('mail');
        }

        return $this->render('mail/index.html.twig', [
            'Form' => $form->createView()
        ]);
    }
}
