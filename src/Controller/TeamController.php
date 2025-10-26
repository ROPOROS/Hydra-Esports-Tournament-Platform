<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Joueur;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    
    /**
     * @Route("/", name="app_team_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $this->getDoctrine()->getRepository(team::class)->findAll();

        foreach ($donnees as $d) {
            $d = $this->fillPlayers($d);
        }

        $teams = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos events)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }
     /**
     * @Route("/liste", name="liste_teamss")
     */
    public function getListeTeams(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $teams = $entityManager
            ->getRepository(Team::class)
            ->findAll();
        $json = $serializerInterface->serialize($teams, 'json');

        return new Response($json);
    }
     /**
     * @Route("/get/{id}", name="get_TeamId", methods={"GET"})
     */
    public function getTeamById(Team $team, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $teams = $entityManager
            ->getRepository(Team::class)
            ->findBy(['id'=>$team->getId()]);
        $json = $serializerInterface->serialize($teams, 'json');

        return new Response($json);
    }
    public function fillPlayers(Team $team): Team
    {
        $players = array();
        $rep = $this->getDoctrine()->getRepository(Joueur::class);
        $x = explode("#", $team->getJoueurs());
        if ($x[0] != null && $x[0] != "NULL" && $x[0] != "null") {
            for ($i = 0; $i < count($x); $i++) {
                if ($x[$i] != "" && $x[$i] != " ") {
                    $index = intval($x[$i]);
                    array_push(
                        $players,
                        $rep->find($index)
                    );
                }
            }
        }
        $team->setPlayers($players);
        return $team;
    }
    /**
     * @Route("/front", name="app_front_team", methods={"GET"})
     * @param EntityManagerInterface $entityManager

     * @return Response
     */
    public function topTeam(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {

        $teams = $entityManager->getRepository(team::class)->findAll();
        $maxMontant=0;
        $team=null;
        foreach($teams as $t ) {
            if ($t->getWallet() > $maxMontant)
            {
                $maxMontant=$t->getWallet();
                $team=$t;
            }

        }


        return $this->render('team/front.html.twig', [
            'teams' => $team,
        ]);
    }
    /**
     * @Route("/new", name="app_team_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $logo
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('logo')->getData();
            $Filename = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images'),
                    $Filename
                );
            } catch (FileException $e) {
            }


            $team->setLogo($Filename);
            $team->setWallet(0);
            $team->setJoueurs(1);
            $user=$this->getDoctrine()->getRepository(Joueur::class )->find($this->getUser());
            $team->setCaptainid($user);
            $entityManager->persist($team);
            $entityManager->flush();


            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_team_show", methods={"GET"})
     */
    public function show(Team $team): Response
    {
        $team = $this->fillPlayers($team);
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_team_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_team_delete", methods={"POST"})
     */
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
