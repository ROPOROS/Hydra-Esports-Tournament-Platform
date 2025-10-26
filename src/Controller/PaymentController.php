<?php

namespace App\Controller;

use App\Repository\PariRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;


class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="app_payment", methods={"GET","POST"})
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }


    /**
     * @Route("/checkout", name="app_payment_checkout", methods={"GET","POST"})
     */
    public function checkout(PariRepository $parirep, Request $request ): Response
    {
        
        $YOUR_DOMAIN = 'http://localhost:8000/home';
        Stripe::setApiKey('sk_test_51Kr33FLwe7UpWWfw1iRcl1uDtdjjkQWcYitMVAxEGnJFDrZDMPgWFye4DqBOU2USEqDdC4dwVWnzP9dVXqAIsnvH0042qcSBcw');


        $prix=isset($_POST['montant']) ? $_POST['montant'] : 12 ;
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                        'name' => 'Coins',
        
                        ],
                        'unit_amount'  => $prix * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN, 
            'cancel_url' => $YOUR_DOMAIN, 
        ]);
        $this->addFlash(
            'info',
            'Achat effectué avec succés'
        );

              // TODO

        if($session){
            $parirep->paywallet($this->getUser(), $prix);
            return $this->redirect($session->url,303);}else{
                return $this->redirectToRoute('app_payment_successurl');
            }


    }


}