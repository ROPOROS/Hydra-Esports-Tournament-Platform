<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Pari;
use App\Entity\Produit;
use App\Entity\Team;
use App\Entity\Tmatchs;
use App\Form\PariType;
use App\Repository\PariRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;
use Knp\Component\Pager\PaginatorInterface;
use RobThree\Auth\TwoFactorAuth;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home" , methods={"GET", "POST"})
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, EntityManagerInterface $entityManagerp, Request $request, PariRepository $parrep): Response
    {
        $error = 0;
        $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();
        $teams = $entityManager
            ->getRepository(Team::class)
            ->findAll();
        $products = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        $products =  $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            3
        );

        $_POST['QRcode'] = isset($_POST['QRcode'])? $_POST['QRcode']: "";

        $tfa = new TwoFactorAuth();
        $valid = false;

        $secretCode = $tfa->createSecret();


        $valid = $tfa->verifyCode($secretCode, "1234") 



        //Verif
        ?
        
        
        Utils::verifyCode($_POST['QRcode']) : Utils::verifyCode($_POST['QRcode']);


        // pari

        $pari = new Pari();
        $form = $this->createForm(PariType::class, $pari);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $match = $this->getDoctrine()->getRepository(Tmatchs::class)
                    ->find($_POST['idmatch']);
                $joueur = $this->getDoctrine()->getRepository(Joueur::class)
                    ->find($_POST['iduser']);

                # TODO
                $pari->setIduser($joueur);
                $pari->setIdequipe($_POST['idequipe']);
                $pari->setIdmatch($match);

                if (Utils::isNotValidMontant($pari->getMontant()))
                    $error = 1;
                elseif ($pari->getMontant() > $pari->getIdUser()->getWallet()) {
                    $error = 2;
                }
                elseif(! $valid){
                    $error = 3;

                }
                else {
                    $entityManagerp->persist($pari);
                    $entityManagerp->flush();
                    $parrep->removemoneyfromwallet($joueur, $pari->getMontant(), 0);


                    $this->addFlash(
                        'info',
                        'Ajout avec succès'
                    );


                    // Your Account SID and Auth Token from twilio.com/console
                    $account_sid = 'AC99487286eeb3dcb0df64834f4c7d6b3c';
                    $auth_token = 'e2d2256fd5c830402859dc7a1f0848c3';
                    // In production, these should be environment variables. E.g.:
                    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

                    // A Twilio number you own with SMS capabilities
                    $twilio_number = "+19378585152";

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create(
                        // Where to send a text message (your cell phone?)
                        '+21624191425',
                        array(
                            'from' => $twilio_number,
                            'body' => 'merci davoir parié sur ce match, allez dans la section mybets pour consulter vos paris!'
                        )
                    );



                    return $this->redirectToRoute('app_pari_mybets', [
                        'idjoueur' => $joueur->getId(),
                    ], Response::HTTP_SEE_OTHER);
                }
            } catch (\Exception $e) {

                $this->addFlash(
                    'info',
                    'vous avez déjà parié sur ce match, si vous voulez faire une modification, vous devez le faire dans cette fenêtre'
                );

                return $this->redirectToRoute('app_pari_mybets', [
                    'idjoueur' => $joueur->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tmatchs' => $tmatchs,
            'form' => $form->createView(),
            'error' => $error,
            'teams' => $teams,
            'products' => $products
        ]);
    }
    /**
     * @Route("/", name="app_home1" , methods={"GET", "POST"})
     */
    public function index1(EntityManagerInterface $entityManager, EntityManagerInterface $entityManagerp, Request $request, PariRepository $parrep, PaginatorInterface $paginator): Response
    {
        $error = 0;
        $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();
        $teams = $entityManager
            ->getRepository(Team::class)
            ->findAll();
        $products = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        $products =  $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            3
        );

        $_POST['QRcode'] = isset($_POST['QRcode'])? $_POST['QRcode']: "";

        $tfa = new TwoFactorAuth();
        $valid = false;

        $secretCode = $tfa->createSecret();


        $valid = $tfa->verifyCode($secretCode, "1234") 



        //Verif
        ?
        
        
        Utils::verifyCode($_POST['QRcode']) : Utils::verifyCode($_POST['QRcode']);


        // pari

        $pari = new Pari();
        $form = $this->createForm(PariType::class, $pari);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $match = $this->getDoctrine()->getRepository(Tmatchs::class)
                    ->find($_POST['idmatch']);
                $joueur = $this->getDoctrine()->getRepository(Joueur::class)
                    ->find($_POST['iduser']);

                # TODO
                $pari->setIduser($joueur);
                $pari->setIdequipe($_POST['idequipe']);
                $pari->setIdmatch($match);

                if (Utils::isNotValidMontant($pari->getMontant()))
                    $error = 1;
                elseif ($pari->getMontant() > $pari->getIdUser()->getWallet()) {
                    $error = 2;
                }
                elseif(! $valid){
                    $error = 3;

                }
                else {
                    $entityManagerp->persist($pari);
                    $entityManagerp->flush();
                    $parrep->removemoneyfromwallet($joueur, $pari->getMontant(), 0);


                    $this->addFlash(
                        'info',
                        'Ajout avec succès'
                    );


                    // Your Account SID and Auth Token from twilio.com/console
                    $account_sid = 'AC99487286eeb3dcb0df64834f4c7d6b3c';
                    $auth_token = 'e2d2256fd5c830402859dc7a1f0848c3';
                    // In production, these should be environment variables. E.g.:
                    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

                    // A Twilio number you own with SMS capabilities
                    $twilio_number = "+19378585152";

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create(
                        // Where to send a text message (your cell phone?)
                        '+21624191425',
                        array(
                            'from' => $twilio_number,
                            'body' => 'merci davoir parié sur ce match, allez dans la section mybets pour consulter vos paris!'
                        )
                    );



                    return $this->redirectToRoute('app_pari_mybets', [
                        'idjoueur' => $joueur->getId(),
                    ], Response::HTTP_SEE_OTHER);
                }
            } catch (\Exception $e) {

                $this->addFlash(
                    'info',
                    'vous avez déjà parié sur ce match, si vous voulez faire une modification, vous devez le faire dans cette fenêtre'
                );

                return $this->redirectToRoute('app_pari_mybets', [
                    'idjoueur' => $joueur->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tmatchs' => $tmatchs,
            'form' => $form->createView(),
            'error' => $error,
            'teams' => $teams,
            'products' => $products
        ]);
    }
}
