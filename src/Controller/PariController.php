<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Pari;
use App\Entity\Tmatchs;
use App\Form\PariType;
use App\Repository\PariRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;

/**
 * @Route("/pari")
 */
class PariController extends Controller
{
    /**
     * @Route("/", name="app_pari_index", methods={"GET"})
     */
    public function index( EntityManagerInterface $entityManager, Request $request): Response
    {
        $paris = $entityManager
            ->getRepository(Pari::class)
            ->findAll();

        // $em = $this->getDoctrine()->getManager();
        // $dql = "SELECT p from App\Entity\Pari";
        // $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $paris = $paginator->paginate($paris, /* query NOT result */
            $request->query->getInt('page', 1), $request->query->getInt('limit', 5)/*limit per page*/   
        );

        $paris->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
            'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
            'style' => 'bottom',
            'span_class' => 'whatever',
        ]);


        return $this->render('pari/index.html.twig', [
            'paris' => $paris,
        ]);
    }

    // TODO Mobile

    /**
     * @Route("/showmobileallparis", name="app_pari_index_mobile", methods={"GET", "POST"})
     */
    public function showPariMobile( EntityManagerInterface $entityManager): Response
    {
        $paris = $entityManager
            ->getRepository(Pari::class)
            ->findAll();
            

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize($paris);
            return new JsonResponse($formatted);
    }

    /**
     * @Route("/{idjoueur}", name="app_pari_mybets" , requirements={"id":"\d+"} ,methods={"POST","GET"})
     */
    public function showmybets($idjoueur ,PariRepository $rep, Request $request): Response
    {
  
        $paris = $rep->findbyiduser($idjoueur);


        $paginator = $this->get('knp_paginator');

        
        $paris = $paginator->paginate($paris, /* query NOT result */
        $request->query->getInt('page', 1), $request->query->getInt('limit', 3)/*limit per page*/   
    );

    $paris->setCustomParameters([
        'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
        'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
        'style' => 'bottom',
        'span_class' => 'whatever',
    ]);

        return $this->render('pari/mybets.html.twig', [
            'paris' => $paris,

        ]);

    }

    

    /**
     * @Route("/new", name="app_pari_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pari = new Pari();
        $form = $this->createForm(PariType::class, $pari);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pari);
            $entityManager->flush();

            return $this->redirectToRoute('app_pari_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pari/new.html.twig', [
            'pari' => $pari,
            'form' => $form->createView(),
        ]);
    }

    //TODO MOBILE

    /**
     * @Route("/addpari/mobile/{id}/{idm}", name="app_pari_new_mobile", methods={"GET", "POST"})
     */
    public function addparismobile(Request $request, EntityManagerInterface $entityManager, $id, $idm, PariRepository $parrep): Response
    {
        $pari = new Pari();
        $pari->setMontant($request->query->get("montant"));
        $pari->setIduser($this->getDoctrine()->getRepository(Joueur::class)->find($id));
        $pari->setIdmatch($this->getDoctrine()->getRepository(Tmatchs::class)->find($idm));
        $pari->setIdequipe($request->query->get("idEquipe"));
        $parrep->removemoneyfromwallet($this->getDoctrine()->getRepository(Joueur::class)->find($id), $pari->getMontant(), 0);

            $entityManager->persist($pari);
            $entityManager->flush();

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



            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Ajout avec succès");
            return new JsonResponse($formatted);
        
    }

    /**
     * @Route("/{iduser}/{idmatch}", name="app_pari_show", methods={"GET"})
     */
    public function show(Pari $pari): Response
    {
        return $this->render('pari/show.html.twig', [
            'pari' => $pari,
    
        ]);
    }


    /**
     * @Route("/{iduser}/{idmatch}/edit", name="app_pari_edit", methods={"GET", "POST"})
     */
    public function edit(PariRepository $rep ,Request $request, $id,$idm ,EntityManagerInterface $entityManager): Response
    {
        
        $pari = $rep->findbyiduserandidmatch($id, $idm);
        $form = $this->createForm(PariType::class, $pari);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pari_mybets', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pari/edit.html.twig', [
            'pari' => $pari,
            'form' => $form->createView(),
        ]);
    }



    // TODO Mobile
    /**
     * @Route("/editmobile/{id}/{idm}", name="app_pari_edit_mobile", methods={"GET", "POST"})
     */
    public function modifierparimobile(Request $request, PariRepository $rep,EntityManagerInterface $entityManager, $id) {

        $pari = $rep->findbyiduserandidmatch($request->get("id"), $request->get("idm"));
        $montant = $pari[0]->getMontant();
        $pari[0]->setMontant($request->get("montant"));
        $pari[0]->setIdequipe($request->get("idEquipe"));

        $entityManager->persist($pari[0]);
        $entityManager->flush();

        $rep->removemoneyfromwallet($this->getDoctrine()->getRepository(Joueur::class)->find($id), $pari[0]->getMontant(),  $montant);
            

        
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("modification avec succès");
        return new JsonResponse($formatted);
        
    }

    /**
     * @Route("/{id}/{idm}", name="app_pari_delete", methods={"POST"})
     */
    public function delete($id, $idm, PariRepository $rep, EntityManagerInterface $entityManager): Response
    {
        $pari = new Pari();
        // if ($this->isCsrfTokenValid('delete'.$pari->getIduser().getId(), $request->request->get('_token'))) {
        //     $entityManager->remove($pari);
        //     $entityManager->flush();
        // }

        $pari = $rep->findbyiduserandidmatch($id, $idm);
        $rep->deleteparibackmoney($id, $pari[0]->getMontant());
        $entityManager->remove($pari[0]);
        $entityManager->flush();

        
        $this->addFlash(
            'info',
            'Suppression avec succès'
        );

        return $this->redirectToRoute('app_pari_mybets', [
            'idjoueur' => $id,
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/edit/{id}/{idm}", name="app_pari_modifier", methods={"GET", "POST"})
     */
    public function modifierparis($idm, $id, PariRepository $rep){
        $pariToTest = new Pari();
        $pari = $rep->findbyiduserandidmatch($id, $idm);
        $pariToTest = $pari[0];
        if(Utils::isNotValidMontant($_POST['montant']))
        {
        $this->addFlash(
            'info',
            'Modification invalide! Le montant doit etre entre 1 et 999'
        );
    }
        elseif ($_POST['montant'] > ($_POST['oldmontant'] + $pariToTest->getIdUser()->getWallet())) {
            $this->addFlash(
                'info',
                'vous navez pas assez de HYDRACOINS pour parier, vous devez recharger votre portefeuille'
            );
        }
        else {
            $rep->updateparibet($pari, $_POST['montant'], $_POST['idequipe']);
            $rep->removemoneyfromwallet($id, $_POST['montant'],$_POST['oldmontant'] );
            $this->addFlash(
                'info',
                'Modification avec succès'
            );

        }

        return $this->redirectToRoute('app_pari_mybets', [
            'idjoueur' => $id,

        ], Response::HTTP_SEE_OTHER);

    }

     /**
     * @Route("/", name="tri", methods={"GET", "POST"})
     */
    public function Tri(Request $request, PariRepository $repository, EntityManagerInterface $entityManager): Response
    {

        $order = $request->get('type');
        $storingby = $request->get('storingby');
        if ($order == "Sélectionnez l'ordre"){
            $paris = $entityManager
            ->getRepository(Pari::class)
            ->findAll();
            $this->addFlash(
                'info',
                'vous devez choisir lordre de tri'
            );

        }

        else if ($storingby == "trier par :"){
            $paris = $entityManager
            ->getRepository(Pari::class)
            ->findAll();
            $this->addFlash(
                'info',
                'vous devez choisir lélément avec lequel vous allez trier'
            );

        }

        else if ($order == "Croissant") 
        {
            if ($storingby == "Email" )
            $paris = $repository->tri_asc_email();
                else 
                $paris = $repository->tri_asc_montant();
        }
        else  {

            if ($storingby == "Email" )
            $paris = $repository->tri_desc_email();
                else 
                $paris = $repository->tri_desc_montant();
           
      }

        $paginator = $this->get('knp_paginator');
        $paris = $paginator->paginate($paris,$request->query->getInt('page', 1), $request->query->getInt('limit', 5) 
    );

    $paris->setCustomParameters([
        'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
        'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
        'style' => 'bottom',
        'span_class' => 'whatever',
    ]);
        
    

        return $this->render('pari/index.html.twig', ['paris' => $paris
        ]);
    }




// TODO Mobile


    /**
     * @Route("/delete/mobile/{id}/{idm}", name="app_pari_mobile_delete",requirements={"id":"\d+"},  methods={"POST","GET"})
     */
    public function deletemobilepari($id, $idm, PariRepository $rep, EntityManagerInterface $entityManager): Response
    {
        $pari = new Pari();
    
        $pari = $rep->findbyiduserandidmatch($id, $idm);
        $rep->deleteparibackmoney($id, $pari[0]->getMontant());
        $entityManager->remove($pari[0]);
        $entityManager->flush();

        
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("Suppression avec succès");
        return new JsonResponse($formatted);

        
       
    }


}