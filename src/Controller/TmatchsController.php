<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Pari;
use App\Entity\Tmatchs;
use App\Form\TmatchsType;
use App\Repository\PariRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TmatchsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/tmatchs")
 */
class TmatchsController extends Controller
{
    /**
     * @Route("/", name="app_tmatchs_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();

            $paginator = $this->get('knp_paginator');
            $tmatchs = $paginator->paginate($tmatchs, /* query NOT result */
            $request->query->getInt('page', 1), $request->query->getInt('limit', 5)/*limit per page*/    );

            $tmatchs->setCustomParameters([
                'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
                'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
                'style' => 'bottom',
                'span_class' => 'whatever',
            ]);

        return $this->render('tmatchs/index.html.twig', [
            'tmatchs' => $tmatchs,
        ]);
    }



// TODO Mobile


    /**
     * @Route("/show", name="app_tmatchs_index_mobile", methods={"GET", "POST"})
     */
    public function ShowMatchsMobile(EntityManagerInterface $entityManager): Response
    {
        $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize($tmatchs);
            return new JsonResponse($formatted);

    }



    /**
     * @Route("/new", name="app_tmatchs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tmatch = new Tmatchs();
        $form = $this->createForm(TmatchsType::class, $tmatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tmatch->setEtat("BetA");
            $tmatch->setScore("nD");
            $tmatch->setPhase(0);
            $entityManager->persist($tmatch);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Ajout avec succès'
            );

            return $this->redirectToRoute('app_tmatchs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tmatchs/new.html.twig', [
            'tmatch' => $tmatch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tmatchs_show", methods={"GET"})
     */
    public function show(Tmatchs $tmatch): Response
    {
        return $this->render('tmatchs/show.html.twig', [
            'tmatch' => $tmatch,
        ]);
    }



// TODO Mobile
    /**
     * @Route("/edit/{id}", name="app_tmatchs_edit_mobile", methods={"GET", "POST"})
     */
    public function modifierMatch(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $tmatch = $this->getDoctrine()->getManager()->getRepository(Tmatchs::class)
                ->find($request->get("id"));

        $tmatch->setDatematch(new \DateTime('@'.strtotime($request->get("dateMatch")."+ 1 day")));

        $tmatch->setHeurematch($request->get("heureMatch"));

        $em->persist($tmatch);
        $em->flush();

        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize($tmatch);
        return new JsonResponse("Moddification avec succées");
    }



    /**
     * @Route("/{id}/edit", name="app_tmatchs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tmatchs $tmatch, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TmatchsType::class, $tmatch);
        $form->handleRequest($request);
        $error = 0;

        if ($form->isSubmitted() && $form->isValid()) {

            if(Utils::isNegative($tmatch->getHeurematch()))
                $error = 1;
            elseif(Utils::isNotValidHour($tmatch->getHeurematch()))
                $error = 2;
            elseif(Utils::isAnterior($tmatch->getIdtournoi()->getDatedebut()->format('Y-m-d'), $tmatch->getDatematch()->format('Y-m-d'))
            || 
            Utils::isAnterior($tmatch->getDatematch()->format('Y-m-d'), $tmatch->getIdtournoi()->getDatefin()->format('Y-m-d')))
                $error = 3;   
            else {

            $entityManager->flush();

            $this->addFlash(
                'info',
                'Modification avec succès'
            );

            return $this->redirectToRoute('app_tmatchs_index', [], Response::HTTP_SEE_OTHER);
        }
        }

        return $this->render('tmatchs/edit.html.twig', [
            'tmatch' => $tmatch,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }


     // TODO Mobile


    /**
     * @Route("/delete/mobile/{id}", name="app_tmatchs_delete",requirements={"id":"\d+"},  methods={"POST","GET"})
     */
    public function deleteMatchMobile(Request $request, Tmatchs $tmatch, EntityManagerInterface $entityManager, $id): Response
    {
        $tmatch = $entityManager->getRepository(Tmatchs::class)->find($id);
        if ($tmatch != null) {
            $entityManager->remove($tmatch);
            $entityManager->flush();
    

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Suppression avec succès");
            return new JsonResponse($formatted);
 
        }
            
    }

    /**
     * @Route("/{id}", name="app_tmatchs_delete", methods={"POST"})
     */
    public function delete(Request $request, Tmatchs $tmatch, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tmatch->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tmatch);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Suppression avec succès'
            );
        }

        return $this->redirectToRoute('v', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/mymatch/{iduser}", name="app_tmatchs_mine", requirements={"id":"\d+"} , methods={"POST","GET"})
     */
    public function showmymatch(TmatchsRepository $rep, $iduser): Response
    {
         $tmatchs = $rep->findmymatch($iduser);

        return $this->render('tmatchs/mymatch.html.twig', [
            'tmatchs' => $tmatchs,
            
        ]);
    }

    /**
     * @Route("/mymatch/{iduser}/wewon/{id}", name="app_tmatchs_wewon", methods={"POST","GET"})
     */

     public function wewon(TmatchsRepository $rep, $id, $iduser, PariRepository $prep) 
     {
        $totalbetonpari = 0.0;
        $totalbetonwinningteam = 0.0;

         $tmatch = $this->getDoctrine()->getRepository(Tmatchs::class)->find($id);
         //$connectedUser = $this->getDoctrine()->getRepository(Joueur::class)->find($iduser);
         if ($tmatch->getidequipea()->getCaptainid()->getId() == $iduser)
                {
                    $score = "A";
                }
                else {
                    $score = "B";
                }
         $rep->wewonourmatch($tmatch, $score);


        $paris = $prep->findbyidmatch($tmatch);
        foreach ($paris as $pari) {
            $totalbetonpari += $pari->getmontant();
        }

        $paris = $prep->findbyidmatchandwinningteam($tmatch, $score);
        foreach ($paris as $pari) {
            $totalbetonwinningteam += $pari->getmontant();
        }

        if ($totalbetonwinningteam == 0) {
            $totalbetonwinningteam = 1;
        }

        $taux = $totalbetonpari/$totalbetonwinningteam;

        foreach ($paris as $pari) {
           $prep->updateWallet($pari->getiduser(), $pari->getmontant() * $taux);
        }
        

         $this->addFlash(
            'info',
            'En cas de tricherie, votre équipe sera disqualifiée de ce tournoi.'
        );


         return $this->redirectToRoute('app_tmatchs_mine', [
             'iduser' => $iduser,
         ], Response::HTTP_SEE_OTHER);
         
     }

     //TODO Mobile

    /**
     * @Route("/mymatch/mobile/{iduser}", name="app_tmatchs_mine_mobile", requirements={"id":"\d+"} , methods={"POST","GET"})
     */
    public function showmymatchmobile(TmatchsRepository $rep, $iduser): Response
    {
         $tmatchs = $rep->findmymatch($iduser);

         $serialize = new Serializer([new ObjectNormalizer()]);
         $formatted = $serialize->normalize($tmatchs);
         return new JsonResponse($formatted);
            
     
    }


      /**
     * @Route("/", name="triM", methods={"GET", "POST"})
     */
    public function TriM(Request $request, TmatchsRepository $repository, EntityManagerInterface $entityManager): Response
    {

        $order = $request->get('type');
        $storingby = $request->get('storingby');
        if ($order == "Sélectionnez l'ordre"){
            $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();
            $this->addFlash(
                'info',
                'vous devez choisir lordre de tri'
            );

        }

        else if ($storingby == "trier par :"){
            $tmatchs = $entityManager
            ->getRepository(Tmatchs::class)
            ->findAll();
            $this->addFlash(
                'info',
                'vous devez choisir lélément avec lequel vous allez trier'
            );

        }

        else if ($order == "Croissant") 
        {
            if ($storingby == "Date" )
                $tmatchs = $repository->tri_asc_date();
            else if ($storingby == "Etat" )
                $tmatchs = $repository->tri_asc_etat();
            else 
                $tmatchs = $repository->tri_asc_nomTournoi();
        }
        else  {
            if ($storingby == "Date" )
                $tmatchs = $repository->tri_desc_date();
            else if ($storingby == "Etat" )
                $tmatchs = $repository->tri_desc_etat();
            else 
                $tmatchs = $repository->tri_desc_nomTournoi();
          
      }

        $paginator = $this->get('knp_paginator');
        $tmatchs = $paginator->paginate($tmatchs,$request->query->getInt('page', 1), $request->query->getInt('limit', 5) 
    );

    $tmatchs->setCustomParameters([
        'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
        'size' => 'medium', # small|large (for template: twitter_bootstrap_v4_pagination)
        'style' => 'bottom',
        'span_class' => 'whatever',
    ]);
        
    
        return $this->render('tmatchs/index.html.twig', ['tmatchs' => $tmatchs
        ]);
    }


    // TODO Mobile


    /**
     * @Route("/mymatch/mobile/{iduser}/wewon/{id}", name="app_tmatchs_wewon_mobile", methods={"POST","GET"})
     */

    public function wewonmobile(TmatchsRepository $rep, $id, $iduser, PariRepository $prep) 
    {
       $totalbetonpari = 0.0;
       $totalbetonwinningteam = 0.0;

        $tmatch = $this->getDoctrine()->getRepository(Tmatchs::class)->find($id);
        //$connectedUser = $this->getDoctrine()->getRepository(Joueur::class)->find($iduser);
        if ($tmatch->getidequipea()->getCaptainid()->getId() == $iduser)
               {
                   $score = "A";
               }
               else {
                   $score = "B";
               }
        $rep->wewonourmatch($tmatch, $score);


       $paris = $prep->findbyidmatch($tmatch);
       foreach ($paris as $pari) {
           $totalbetonpari += $pari->getmontant();
       }

       $paris = $prep->findbyidmatchandwinningteam($tmatch, $score);
       foreach ($paris as $pari) {
           $totalbetonwinningteam += $pari->getmontant();
       }

       if ($totalbetonwinningteam == 0) {
           $totalbetonwinningteam = 1;
       }

       $taux = $totalbetonpari/$totalbetonwinningteam;

       foreach ($paris as $pari) {
          $prep->updateWallet($pari->getiduser(), $pari->getmontant() * $taux);
       }
       


       $serialize = new Serializer([new ObjectNormalizer()]);
       $formatted = $serialize->normalize("you won avec succès");
       return new JsonResponse($formatted);
        
    }

}
