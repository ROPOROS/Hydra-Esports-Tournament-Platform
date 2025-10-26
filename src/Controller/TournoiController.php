<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Entity\Team;
use App\Entity\Tmatchs;
use App\Entity\Jeu;
use App\Entity\Joueur;
use App\Form\TournoiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\Utils;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/tournoi")
 */
class TournoiController extends AbstractController
{
    /**
     * @Route("/", name="tournoi_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {

        $error = $request->query->getInt('error', 0);
        $tournoiPhase = $request->query->getInt('tournoiPhase', 0);
        $idJeu = $request->query->getInt('id', 0);
        if ($idJeu) {
            $tournois = $this->getDoctrine()
                ->getRepository(Tournoi::class)
                ->findBy(["idjeu" => $idJeu]);
        } else {
            $tournois = $entityManager
                ->getRepository(Tournoi::class)
                ->findAll();
        }

        $jeux = $entityManager
            ->getRepository(Jeu::class)
            ->findAll();

        for ($i = 0; $i < count($tournois); $i++)
            $tournois[$i] = $this->fillTeams($tournois[$i]);


        $tournois =  $paginator->paginate(
            $tournois,
            $request->query->getInt('page', 1),
            2
        );
        $teamId = $this->getUser() != null ?
            $entityManager->getRepository(Team::class)->findOneBy(["captainid" => $this->getUser()])->getId()
            : 0;

        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
            'jeux' => $jeux,
            'error' => $error,
            'tournoiPhase' => $tournoiPhase,
            'teamId' => $teamId,
            'idJeu' => $idJeu
        ]);
    }

    public function fillTeams(Tournoi $tournoi): Tournoi
    {
        $teams = array();
        $rep = $this->getDoctrine()->getRepository(Team::class);
        $x = explode("#", $tournoi->getEquipes());
        if ($x[0] != null && $x[0] != "NULL" && $x[0] != "null") {
            for ($i = 0; $i < count($x); $i++) {
                if ($x[$i] != "" && $x[$i] != " ") {
                    $index = intval($x[$i]);
                    array_push(
                        $teams,
                        $rep->find($index)
                    );
                }
            }
        }

        $tournoi->setEq($teams);
        return $tournoi;
    }
    /**
     * @Route("/liste", name="liste_tournoi")
     */
    public function getListeTournois(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $tournois = $entityManager
            ->getRepository(Tournoi::class)
            ->findAll();
        $json = $serializerInterface->serialize($tournois, 'json');

        return new Response($json);
    }
    /**
     * @Route("/create", name="create_tournoi")
     */
    public function addTournoi(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request)
    {
        $content = $request->getContent();
        $tournoi = new Tournoi();
        $exemple = $entityManager->getRepository(Tournoi::class)->findAll();
        $data = explode("&", $content);
        $tournoi = clone $exemple[0];
        $tournoi->setNom(explode("=", $data[0])[1]);
        $tournoi->setPrix(explode("=", $data[1])[1]);
        $tournoi->setDetails(explode("=", $data[2])[1]);
        $tournoi->setHeure(explode("=", $data[3])[1]);
        $jeu = $entityManager->getRepository(Jeu::class)->find(explode("=", $data[4])[1]);
        $tournoi->setIdJeu($jeu);
        $tournoi->setEquipes("");
        $tournoi->setPhase(explode("=", $data[5])[1]);
        $tournoi->setDatedebut(new DateTime(explode("=", $data[6])[1]));
        $tournoi->setDatefin(new DateTime(explode("=", $data[7])[1]));
        $entityManager->persist($tournoi);
        $entityManager->flush();
        return new Response('Tournoi ajouté');
    }
    /**
     * @Route("/editTournoi", name="edit_tournoi1")
     */
    public function editTournoi(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request)
    {
        $content = $request->getContent();
        $data = explode("&", $content);
        $tournoi = $entityManager->getRepository(Tournoi::class)->find(explode("=", $data[7])[1]);

        $tournoi->setNom(explode("=", $data[0])[1]);
        $tournoi->setPrix(explode("=", $data[1])[1]);
        $tournoi->setDetails(explode("=", $data[2])[1]);
        $tournoi->setHeure(explode("=", $data[3])[1]);
        $jeu = $entityManager->getRepository(Jeu::class)->find(explode("=", $data[4])[1]);
        $tournoi->setIdJeu($jeu);
        $tournoi->setDatedebut(new DateTime(explode("=", $data[5])[1]));
        $tournoi->setDatefin(new DateTime(explode("=", $data[6])[1]));

        $entityManager->persist($tournoi);
        $entityManager->flush();
        return new Response('Tournoi modifié');
    }
    /**
     * @Route("/deleteTournoi", name="delete_tournoi1" , methods={"GET", "POST"})
     */
    public function deleteTournoi(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request)
    {
        $content = $request->getContent();
         $data = explode("&", $content);
        $tournoi = $entityManager->getRepository(Tournoi::class)->find(explode("=", $data[0])[1]);

        $entityManager->remove($tournoi);
        $entityManager->flush();
        return new Response('tournoi supprimé');
    }
    /**
     * @Route("/verify", name="verify_tournoi" , methods={"GET", "POST"})
     */
    public function verifyCode(Request $request)
    {
        $code = $request->query->get('code', 0);
        $content = $request->getContent();
        $code = Utils::verifyCode($code) ? "true" : 'false';
        //$code = explode("=", $content)[1];
        return new Response($code);
    }


    /**
     * @Route("/{id}/start", name="tournoi_start", methods={"GET"})
     */
    public function initiateTournoi(Tournoi $tournoi)
    {

        $tournoi = $this->fillTeams($tournoi);
        $equipes = $tournoi->getEq();
        shuffle($equipes);
        $tournoi->setEq($equipes);
        $error = 0;

        if (count($tournoi->getEq()) >= 4) {
            if (count($tournoi->getEq()) % 4 == 0) {
                $entityManager = $this->getDoctrine()->getManager();
                for ($i = 0; $i < (count($tournoi->getEq())); $i++) {
                    $tmatch = new Tmatchs();
                    $tmatch->setEtat("betA");
                    $tmatch->setDatematch(new DateTime("now"));
                    $tmatch->setScore("nD");
                    $tmatch->setHeurematch(5);
                    $tmatch->setIdequipea($tournoi->getEq()[$i]);
                    $tmatch->setIdequipeb($tournoi->getEq()[++$i]);
                    $tmatch->setPhase(0);
                    $tmatch->setIdtournoi($tournoi);

                    $entityManager->persist($tmatch);
                }
                $tournoi->setPhase(0);
                $equipesString = "";
                foreach ($equipes as $e) {
                    $equipesString .= $e->getId() . "#";
                }
                $tournoi->setEquipes($equipesString);
                $entityManager->persist($tournoi);
                $entityManager->flush();
            } else
                $error = 3;
        } else
            $error = 1;

        return $this->redirectToRoute('tournoi_index', $error ? array('error' => $error) : array(), Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}/join/{userId}", name="tournoi_join", methods={"GET"})
     */
    public function joinTournoi(Tournoi $tournoi, $userId)
    {

        $tournoi = $this->fillTeams($tournoi);
        $equipes = $tournoi->getEq();
        $em = $this->getDoctrine()->getManager();
        $joueur = $em->getRepository(Joueur::class)->find($userId);
        $error = 0;
        $team = $em->getRepository(Team::class)->findOneBy(array('captainid' => $joueur->getId()));
        if ($team) {
            array_push($equipes, $team);
            $tournoi->setEq($equipes);
            $equipesString = "";
            foreach ($equipes as $e) {
                $equipesString .= $e->getId() . "#";
            }
            $tournoi->setEquipes($equipesString);
            $em->persist($tournoi);
            $em->flush();
        } else {
            $error = 4;
        }

        return $this->redirectToRoute('tournoi_index', $error ? array('error' => $error) : array(), Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/{id}/next", name="tournoi_next", methods={"GET"})
     */
    public function nextPhase(Tournoi $tournoi)
    {

        $tournoi = $this->fillTeams($tournoi);
        $tmatchs = $this->getDoctrine()->getRepository(Tmatchs::class)
            ->findBy(array("idtournoi" => $tournoi->getId()));
        $phaseFini = true;
        $removable = array();
        foreach ($tmatchs as $m) {
            if ($m->getPhase() == $tournoi->getPhase() && $m->getScore() == "nD") {
                $phaseFini = false;
            }
            if ($m->getScore() == "A" || $m->getScore() == "B") {
                for ($i = 0; $i < sizeof($tournoi->getEq()); $i++) {
                    $t = $tournoi->getEq()[$i];
                    if ($m->getScore() == "A" && $t->getId() == $m->getIdequipeb()->getId())
                        array_push($removable, $i);
                    else if ($m->getScore() == "B" && $t->getId() == $m->getIdequipea()->getId())
                        array_push($removable, $i);
                }
                $newEquipe = array();

                for ($i = 0; $i < sizeof($tournoi->getEq()); $i++) {
                    if (!in_array($i, $removable))
                        array_push($newEquipe, $tournoi->getEq()[$i]);
                }
            }
        }
        $error = 0;

        $entityManager = $this->getDoctrine()->getManager();

        if ($phaseFini) {
            if (count($newEquipe) == 1) {
                $tournoi->setPhase($tournoi->getPhase() + 1);
                $teams = "";
                foreach ($newEquipe as $team) {
                    $teams .= $team->getId() . "#";
                }
                $tournoi->setEquipes($teams);

                $entityManager->persist($tournoi);
                $entityManager->flush();
            } else {
                for ($i = 0; $i < (count($newEquipe)); $i++) {
                    $tmatch = new Tmatchs();
                    $tmatch->setEtat("betnA");
                    $tmatch->setDatematch(new DateTime("now"));
                    $tmatch->setScore("nD");
                    $tmatch->setHeurematch(5);
                    $tmatch->setIdequipea($newEquipe[$i]);
                    $i++;
                    $tmatch->setIdequipeb($newEquipe[$i]);
                    $tmatch->setPhase($tournoi->getPhase() + 1);
                    $tmatch->setIdtournoi($tournoi);

                    $entityManager->persist($tmatch);
                }
                $tournoi->setPhase($tournoi->getPhase() + 1);
                $teams = "";
                foreach ($newEquipe as $team) {
                    $teams .= $team->getId() . "#";
                }
                $tournoi->setEquipes($teams);

                $entityManager->persist($tournoi);
                $entityManager->flush();
            }
        } else
            $error = 2;



        return $this->redirectToRoute(
            'tournoi_index',
            !$phaseFini ? array('error' => $error, 'tournoiPhase' => $tournoi->getPhase()) : array(),
            Response::HTTP_SEE_OTHER
        );
    }




    /**
     * @Route("/new", name="tournoi_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);
        $error = 0;



        if ($form->isSubmitted() && $form->isValid()) {



            if (Utils::isTournoiEmpty($tournoi))
                $error = 1;
            elseif (Utils::isAnterior($tournoi->getDateDebut()->format('Y-m-d'), $tournoi->getDateFin()->format('Y-m-d')))
                $error = 2;
            elseif (Utils::isNegative($tournoi->getPrix()))
                $error = 3;
            elseif (Utils::isNotValidHour($tournoi->getHeure()))
                $error = 4;
            elseif (Utils::isAnterior((new DateTime())->format('Y-m-d'), $tournoi->getDateDebut()->format('Y-m-d')))
                $error = 5;


            if ($error == 0) {
                $tournoi->setPhase(-1);
                $entityManager->persist($tournoi);
                $entityManager->flush();

                return $this->redirectToRoute('tournoi_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
    /**
     * @Route("/{id}/brackets", name="tournoi_brackets", methods={"GET"})
     */
    public function showBrackets(Tournoi $tournoi): Response
    {
        $tmatchs = $this->getDoctrine()->getRepository(Tmatchs::class)
            ->findBy(["idtournoi" => $tournoi->getId()]);
        $number = 0;
        $lastPhase = $tmatchs[count($tmatchs) - 1]->getPhase();
        $lastPhaseNumber = 0;
        foreach ($tmatchs as $t) {
            if ($t->getPhase() == 0)
                $number += 2;
            if ($t->getPhase() == $lastPhase)
                $lastPhaseNumber++;
        }
        // $teamToDisplay = array();
        // foreach ($tmatchs as $t) {
        //     if ($t->getPhase() == $lastPhase && $t->getScore() != "nD") {
        //         if ($t->getScore() == "A")
        //             array_push($teamToDisplay, $t->getidequipea()->getNom());
        //         else
        //             array_push($teamToDisplay, $t->getidequipeb()->getNom());
        //     }
        // }
        $indexDisplay = 0;

        while (count($tmatchs)   < $number - 1) {
            $tmatch = new Tmatchs();
            $team1 = new Team();
            // if (
            //     $indexDisplay < ($lastPhaseNumber / 2) &&
            //     $tmatchs[count($tmatchs) - $lastPhaseNumber]->getScore() != "nD"
            // ) {
            //     if ($tmatchs[count($tmatchs) - $lastPhaseNumber]->getScore() == "A")
            //         $team1->setNom($tmatchs[count($tmatchs) - $lastPhaseNumber]->getidequipea()->getNom());
            //     else
            //         $team1->setNom($tmatchs[count($tmatchs) - $lastPhaseNumber]->getidequipeb()->getNom());
            // } else
            $team1->setNom("?");
            $team2 = new Team();
            // if (
            //     $indexDisplay < ($lastPhaseNumber / 2) &&
            //     $tmatchs[count($tmatchs) - $lastPhaseNumber]->getScore() != "nD"
            // ) {
            //     if ($tmatchs[count($tmatchs) - $lastPhaseNumber+1]->getScore() == "A")
            //         $team2->setNom($tmatchs[count($tmatchs) - $lastPhaseNumber+1]->getidequipea()->getNom());
            //     else
            //         $team2->setNom($tmatchs[count($tmatchs) - $lastPhaseNumber+1]->getidequipeb()->getNom());
            // } else
            $team2->setNom("?");
            $tmatch->setIdequipea($team1);
            $tmatch->setIdequipeb($team2);
            $tmatch->setPhase(-1);
            $tmatch->setScore("nD");
            array_push($tmatchs, $tmatch);
            $indexDisplay += 2;
        }



        return $this->render('tournoi/brackets.html.twig', [
            'tournoi' => $tournoi,
            'tmatchs' => $tmatchs,
        ]);
    }
    /**
     * @Route("/{id}", name="tournoi_show", methods={"GET"})
     */
    public function show(Tournoi $tournoi): Response
    {
        $tournoi = $this->fillTeams($tournoi);
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tournoi_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);
        $error = 0;

        if ($form->isSubmitted() && $form->isValid()) {

            if (Utils::isTournoiEmpty($tournoi))
                $error = 1;
            elseif (Utils::isAnterior($tournoi->getDateDebut()->format('Y-m-d'), $tournoi->getDateFin()->format('Y-m-d')))
                $error = 2;
            elseif (Utils::isNegative($tournoi->getPrix()))
                $error = 3;
            elseif (Utils::isNotValidHour($tournoi->getHeure()))
                $error = 4;

            if ($error == 0) {
                $entityManager->flush();

                return $this->redirectToRoute('tournoi_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{id}", name="tournoi_delete", methods={"POST"})
     */
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tournoi->getId(), $request->request->get('_token'))) {
            $tmatchs = $this->getDoctrine()->getRepository(Tmatchs::class)
                ->findBy(array("idtournoi" => $tournoi->getId()));
            foreach ($tmatchs as $tmatch)
                $entityManager->remove($tmatch);

            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournoi_index', [], Response::HTTP_SEE_OTHER);
    }
}
