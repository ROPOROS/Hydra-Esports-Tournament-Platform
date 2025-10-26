<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Form\JeuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\Utils;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/jeu")
 */
class JeuController extends AbstractController
{
    /**
     * @Route("/", name="jeu_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {

        $test = $request->query->get('type', 0);

        $jeux = $entityManager
            ->getRepository(Jeu::class)
            ->findAll();

        $types = array();
        foreach ($jeux as $j) {
            if (!in_array($j->getType(), $types))
                array_push($types, $j->getType());
        }
        if ($test && $test !="null") {
            $jeux = $entityManager
                ->getRepository(Jeu::class)
                ->findBy(["type" => $test]);
        }

        $jeux =  $paginator->paginate(
            $jeux,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('jeu/index.html.twig', [
            'jeux' => $jeux,
            'types' => $types,
            'idJeu'=>$test
        ]);
    }

    /**
     * @Route("/new", name="jeu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);
        $error = 0;

        if ($form->isSubmitted() && $form->isValid()) {

            if (Utils::isNegative($jeu->getNombrejoueursnecessaires()))
                $error = 1;
            if ($error == 0) {
                $entityManager->persist($jeu);
                $entityManager->flush();

                return $this->redirectToRoute('jeu_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('jeu/new.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
    /**
     * @Route("/liste", name="liste_jeu")
     */
    public function getListeJeux(EntityManagerInterface $entityManager,SerializerInterface $serializerInterface)
    {
        $jeux = $entityManager
        ->getRepository(Jeu::class)
        ->findAll();
        $json = $serializerInterface->serialize($jeux,'json');

        return new Response($json);
    }
     /**
     * @Route("/get/{id}", name="get_jeuId", methods={"GET"})
     */
    public function getJeuById(Jeu $jeu, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {

        $jeux = $entityManager
            ->getRepository(Jeu::class)
            ->findBy(['id'=>$jeu->getId()]);
        $json = $serializerInterface->serialize($jeux, 'json');

        return new Response($json);
    }
    /**
     * @Route("/create", name="create_jeu")
     */
    public function addJeu(EntityManagerInterface $entityManager,SerializerInterface $serializerInterface,Request $request)
    {
         $content = $request->getContent();
         $jeu = new Jeu();
         $exemple = $entityManager->getRepository(Jeu::class)->findAll();
         $data = explode("&",$content);
         $jeu = clone $exemple[0];
         $jeu->setNom(explode("=",$data[0])[1]);
         $jeu->setType(explode("=",$data[1])[1]);
         $jeu->setNombrejoueursnecessaires(explode("=",$data[2])[1]);
         $jeu->setImage(explode("=",$data[3])[1]);
          $entityManager->persist($jeu);
          $entityManager->flush();
        return new Response('Jeu ajouté');
    }
    /**
     * @Route("/edit", name="create_edit")
     */
    public function editJeu(EntityManagerInterface $entityManager,SerializerInterface $serializerInterface,Request $request)
    {
         $content = $request->getContent();
         $data = explode("&",$content);
         $jeu = $entityManager->getRepository(Jeu::class)->find(explode("=",$data[4])[1]);
         $jeu->setNom(explode("=",$data[0])[1]);
         $jeu->setType(explode("=",$data[1])[1]);
         $jeu->setNombrejoueursnecessaires(explode("=",$data[2])[1]);
         $jeu->setImage(explode("=",$data[3])[1]);
          $entityManager->persist($jeu);
          $entityManager->flush();
        return new Response('Jeu modifié');
    }
    /**
     * @Route("/deleteJeu/mobile/{id}", name="delete_jeu1_mobile", methods={"GET", "POST"})
     */
    public function deleteJeu(EntityManagerInterface $entityManager,SerializerInterface $serializerInterface,Request $request)
    {
         $content = $request->getContent();
         $data = explode("&",$content);

        $idJeu = $request->query->getInt('id', 0);
         $jeu = $entityManager->getRepository(Jeu::class)->find(explode("=",$data[0])[1]);
         
          $entityManager->remove($jeu);
          $entityManager->flush();
        return new Response('Jeu added');
    }

    /**
     * @Route("/{id}", name="jeu_show", methods={"GET"})
     */
    public function show(Jeu $jeu): Response
    {
        return $this->render('jeu/show.html.twig', [
            'jeu' => $jeu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="jeu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Jeu $jeu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);
        $error = 0;

        if ($form->isSubmitted() && $form->isValid()) {

            if (Utils::isNegative($jeu->getNombrejoueursnecessaires()))
                $error = 1;
            if ($error == 0) {
                $entityManager->flush();

                return $this->redirectToRoute('jeu_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('jeu/edit.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{id}", name="jeu_delete", methods={"POST"})
     */
    public function delete(Request $request, Jeu $jeu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jeu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jeu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jeu_index', [], Response::HTTP_SEE_OTHER);
    }
}
