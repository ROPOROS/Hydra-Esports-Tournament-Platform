<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Produit;
use App\Entity\Team;
use App\Form\ProduitType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(CommandeRepository  $cr, ProduitRepository $pr, EntityManagerInterface $entityManager): Response
    {
        $produit = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $commandes = $cr->findAll();

        $produits = array();
        foreach ($commandes as $c) {
            $produits[] = $c->getIdProduit();
        }

        // Max
        sort($produits);
        $n = sizeof($produits);
        //sort($produits, $n);
        usort($produits, fn($a, $b) => strcmp($a->getNom(), $b->getNom()));
        $max_count = 1;
        $res = $produits[0];
        $curr_count = 1;
        for ($i = 1; $i < $n; $i++) {
            if ($produits[$i] == $produits[$i - 1])
                $curr_count++;
            else {
                if ($curr_count > $max_count) {
                    $max_count = $curr_count;
                    $res = $produits[$i - 1];
                }
                $curr_count = 1;
            }
        }
        if ($curr_count > $max_count) {
            $max_count = $curr_count;
            $res = $produits[$n - 1];
        }

        // Min
        $min_count = $n - 1;
        $res1 = $produits[0];
        $curr_count = 1;
        for ($i = 1; $i < $n; $i++) {
            if ($produits[$i] == $produits[$i - 1])
                $curr_count++;
            else {
                if ($curr_count < $min_count) {
                    $min_count = $curr_count;
                    $res1 = $produits[$i - 1];
                }
                $curr_count = 1;
            }
        }
        if ($curr_count < $min_count) {
            $min_count = $curr_count;
            $res1 = $produits[$n - 1];
        }


        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
            'res' => $res,
            'res1' => $res1,
        ]);
    }

        /**
         * @Route("/ProduitJson", name="allProduitJson")
         */
        public function ProduitJson(EntityManagerInterface $entityManager
            , NormalizerInterface  $normalizer): Response
        {
            $produit = $entityManager
                ->getRepository(Produit::class)
                ->findAll();
            //json
            $jsonContent= $normalizer->normalize($produit, 'json',['groups'=>'post:read']);

            return new Response(json_encode($jsonContent));
        }


    /**
     * @Route("/backoffice", name="app_produit_index2", methods={"GET"})
     */
    public function index2(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('produit/index2.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();

        //session
        //$user = $this->getDoctrine()->getRepository(Joueur::class)->find($this->getUser());

        $myTeam = $this->getDoctrine()->getRepository(Team::class)
        ->findOneBy(['captainid'=>$this->getUser()]);

        $produit->setIdequipe($myTeam);

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //image :
            $image = $form->get('image')->getData();
            $fichier = $image->getClientOriginalName();;
            //On copie le fichier dans le dossier upload
            $image->move(
                $this->getParameter('upload_directory'),
                $fichier
            );
            // on stocke l'image dans la bdd (son nom)
            $produit->setImage($fichier);


            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

        /**
         * @Route("/newProduitJson", name="new_Produit_Json", methods={"GET", "POST"})
         */
        public function newProduitJson(CommandeRepository  $cr,ProduitRepository $pr,EntityManagerInterface $entityManager
            , NormalizerInterface  $normalizer, Request $request): Response
        {
            $produit = new Produit();
            $produit->setRef($request->get('ref'));
            $produit->setNom($request->get('nom'));
            $produit->setDescription($request->get('description'));
            $produit->setPrix($request->get('prix'));
            $produit->setStock($request->get('stock'));
            $produit->setType($request->get('type'));
            //image
            //$produit->setImage($request->get('image'));
            $produit->setImage("test");
            //session
            $user = $request->get('idEquipe');
            $myTeam = $this->getDoctrine()->getRepository(Team::class)
                ->findOneBy(['captainid'=>$user]);
            $produit->setIdequipe($myTeam);

            $entityManager->persist($produit);
            $entityManager->flush();

            //json
            $jsonContent= $normalizer->normalize($produit, 'json',['groups'=>'post:read']);

            return new Response(json_encode($jsonContent));
        }




    /**
     * @Route("/{id}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $img = $produit->getImage();
        $ancien = new File($this->getParameter('upload_directory') . $img);
        $produit->setImage($ancien);

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $produit->setImage($ancien);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index2', [], Response::HTTP_SEE_OTHER);
    }


    //JSON



    /**
     * @Route("/detailProduitJson/{id}", name="detailProduitJson")
     */
    public function detailProduitJson(NormalizerInterface  $normalizer,ProduitRepository $pr,$id,Produit $produit): Response
    {
        $produit=$pr->findBy(['id'=>$id]);
        $jsonContent= $normalizer->normalize($produit, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/updateProduitJson/{id}", name="update_Produit_Json", methods={"GET", "POST"})
     */
    public function updateProduitJson(CommandeRepository  $cr,ProduitRepository $pr,EntityManagerInterface $entityManager
        , NormalizerInterface  $normalizer, Request $request,$id): Response
    {
        $produit = $pr->find($id);
        $produit->setRef($request->get('ref'));
        $produit->setNom($request->get('nom'));
        $produit->setDescription($request->get('description'));
        $produit->setPrix($request->get('prix'));
        $produit->setStock($request->get('stock'));
        $produit->setType($request->get('type'));
        //$produit->setImage($request->get('image'));
        //session
        /*
         * $user = $request->get('idEquipe');
        $myTeam = $this->getDoctrine()->getRepository(Team::class)
            ->findOneBy(['captainid'=>$user]);
        $produit->setIdequipe($myTeam);
        */
        $entityManager->flush();

        //json
        $jsonContent= $normalizer->normalize($produit, 'json',['groups'=>'post:read']);

        return new Response("Updated".json_encode($jsonContent));
    }

    /**
     * @Route("/deleteProduitJson/{id}", name="delete_Produit_Json", methods={"GET", "POST"})
     */
    public function deleteProduitJson(CommandeRepository  $cr,ProduitRepository $pr,EntityManagerInterface $entityManager
        , NormalizerInterface  $normalizer, Request $request,$id): Response
    {
        $produit = $pr->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        //json
        $jsonContent= $normalizer->normalize($produit, 'json',['groups'=>'post:read']);

        return new Response("Deleted".json_encode($jsonContent));
    }


}
