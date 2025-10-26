<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Joueur;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\JoueurRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Swift_Mailer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="app_commande_index", methods={"GET"})
     */
    public function index(CommandeRepository  $cr,JoueurRepository $jr,ProduitRepository $pr,EntityManagerInterface $entityManager): Response
    {
        //session
        $id = $this->getUser();
        $user=$jr
            ->find($id);
        //session
        $commandes=$cr->findByConfirmed(0, $user);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    /**
     * @Route("/commandeJson/{id}", name="allCommandeJson")
     */
    public function CommandeJson($id,CommandeRepository  $cr,JoueurRepository $jr,EntityManagerInterface $entityManager
        , NormalizerInterface  $normalizer): Response
    {
        //session
        //$id = $this->getUser();
        $user=$jr
            ->find($id);
        $commandes=$cr->findByConfirmed(0, $user);
        //json
        $jsonContent= $normalizer->normalize($commandes, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }
    //PDF
    /**
     * @Route("/print", name="print", methods={"GET"})
     *
     */
    public function print(CommandeRepository  $cr,JoueurRepository $jr,EntityManagerInterface $entityManager,
                          Swift_Mailer $mailer): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);
        //$pdfOptions->set();

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->getOptions()->setChroot('C:\xampp\htdocs\symfony\Hydra-Web-main2\public\assets\img');
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $dompdf->setHttpContext($contxt);

        //session
        $user = $this->getDoctrine()->
        getRepository(Joueur::class)->find($this->getUser());

        //Session
        $commandes=$cr->findByConfirmed(0, $user);


        //commandeConfirme
        foreach ($commandes as $c){
            $c->setConfirme(1);
            $entityManager->flush();
        }

        $html = $this->renderView('commande/print.html.twig', [
            'commandes' => $commandes
        ]);

        //dd($html);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("facture.pdf", ["Attachement" => true]);
        // $dompdf->stream("factureTest.pdf",["Attachement"=>false]);

    }
    /**
     * @Route("/newCommande/{id}/{idj}", name="app_commande_newJson", methods={"GET", "POST"})
     */
    public function newCommande($id,$idj,Request $request, EntityManagerInterface $entityManager,Produit $produit,ProduitRepository $pr, NormalizerInterface  $normalizer): Response
    {
        $commande = new Commande();
        $joueur = new Joueur();
        $produit=$pr->find($id);
        $joueur= $entityManager
            ->getRepository(Joueur::class)
            ->findOneBy(array('id' => $idj));

        $commande->setIdproduit($produit);
        $commande->setIduser($joueur);
        $commande->setDatecommande(new \DateTime(date('Y-m-d')));
        $commande->setConfirme(0);

        $entityManager->persist($commande);
        $entityManager->flush();

        $jsonContent= $normalizer->normalize($commande, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/deleteJson/{id}", name="app_commande_deleteJson")
     */
    public function deleteJson($id,Request $request, NormalizerInterface  $normalizer, Commande $commande, EntityManagerInterface $entityManager,CommandeRepository $cr): Response
    {
        $commande = $cr->find($id);
        $entityManager->remove($commande);
        $entityManager->flush();

        //json
        $jsonContent= $normalizer->normalize($commande, 'json',['groups'=>'post:read']);

        return new Response("Deleted".json_encode($jsonContent));
    }



    /**
     * @Route("/{id}/new", name="app_commande_new", methods={"GET", "POST"})
     */
    //Passer idProduit/idEquipe en param + setter
    public function new(Request $request, EntityManagerInterface $entityManager,Produit $produit): Response
    {
        $commande = new Commande();
        $joueur = new Joueur();
        $joueur= $entityManager
            ->getRepository(Joueur::class)
            ->findOneBy(array('id' => $this->getUser()));

        $commande->setIdproduit($produit);
        $commande->setIduser($joueur);
        $commande->setDatecommande(new \DateTime(date('Y-m-d')));
        $commande->setConfirme(0);
        $entityManager->persist($commande);
        $entityManager->flush();


        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);


    }

    /**
     * @Route("/{id}", name="app_commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    ///**
    // * @Route("/{id}/edit", name="app_commande_edit", methods={"GET", "POST"})
    //*/
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }


    //JSON






}
