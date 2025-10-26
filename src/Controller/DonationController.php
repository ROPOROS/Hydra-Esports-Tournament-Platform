<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Joueur;
use App\Entity\Tournoi;
use App\Entity\Team;
use App\Form\DonationType;
use ContainerMb1c3ct\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/donation")
 */
class DonationController extends AbstractController
{

    
    /**
    * @Route("/dataJson", name="app-show-donation")
    */
    public function showAll(EntityManagerInterface $entityManager,SerializerInterface $serializerInterface)
    {

        $tournois = $entityManager
        ->getRepository(Donation::class)
        ->findAll();
    $json = $serializerInterface->serialize($tournois, 'json');

    return new Response($json);
    }

    /**
     * @Route("/editDonation", name="edit_donation1", methods={"GET", "POST"})
     */
    public function editDonation(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request )
    {
        $content = $request->getContent();
        $data = explode("&", $content);
        $donation = $entityManager->getRepository(Donation::class)->
        find(explode("=", $data[3])[1]);
        //findAll()[0];

        //$donation->setDetails($content);


        $donation->setMontant(explode("=", $data[0])[1]);
        $user = $entityManager->getRepository(Joueur::class)->find(explode("=", $data[1])[1]);
        $team = $entityManager->getRepository(Team::class)->find(explode("=", $data[2])[1]);
        $donation->setIdTeam($team);

        $donation->setIdUser($user);
    

        $entityManager->persist($donation);
        $entityManager->flush();
        return new Response('donation modifié');
    }
    /**
     * @Route("/delete/mobile/{id}", name="delete_donation1")
     */
    public function deleteDonation(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request, $id)
    {
        $content = $request->getContent();
        $data = explode("&", $content);
        $donation = $entityManager->getRepository(Donation::class)->find($id);

        $entityManager->remove($donation);
        $entityManager->flush();
        return new Response('donation supprimé');
    }



    /**
     * @Route("/", name="app_donation_index", methods={"GET"})
     * @param EntityManagerInterface $entityManager

     * @return Response
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator,Request $request): Response
    {
        $donnees = $this->getDoctrine()->getRepository(donation::class)->findAll();

        foreach($donnees as $don){

            $user = $this->getDoctrine()->getRepository(Joueur::class)->find($don->getIduser());
            $team = $this->getDoctrine()->getRepository(Team::class)->find($don->getIdteam());

            $don->setUser($user);
            $don->setTeam($team);
        }



        $donations = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos events)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10// Nombre de résultats par page
        );

        return $this->render('donation/index.html.twig', [
            'donations' => $donations,
        ]);

    }
    /**
     * @Route("/front", name="app_front_donation", methods={"GET"})
     * @param EntityManagerInterface $entityManager

     * @return Response
     */
    public function topDonator(EntityManagerInterface $entityManager, PaginatorInterface $paginator,Request $request): Response
    {

        $donations = $entityManager->getRepository(donation::class)->findAll();
        $maxMontant=0;
        $donation=null;
        foreach($donations as $t ) {
            if ($t->getMontant() > $maxMontant)
            {
                $maxMontant=$t->getMontant();
                $donation=$t;
            }

        }
        return $this->render('donation/front.html.twig', [
            'donations' => $donation,
        ]);

    }


    /*

    
    /**
     * @Route("/stats", name="app_donation_stat", methods={"GET"})
     */
    /*
    public function findTeam(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        usort($teams, function($a, $b){return $a->getWallet() > $b->getWallet();});
        return $this->render('donation/stats.html.twig', [
            'teams' => $teams
        ]);
    }
    */
    /**
     * @Route("/stats", name="app_donation_stat", methods={"GET"})
     */

    public function find(EntityManagerInterface $entityManager): Response
    {

        $donations = $entityManager->getRepository(Donation::class)->findAll();
        $donation = $entityManager->getRepository(Donation::class)->createQueryBuilder('u')
            ->select('sum(u.montant)','u.datedon')
            ->groupBy('u.datedon')
            ->getQuery()
            ->getResult();




        usort($donations,function ($a,$b){return $a->getMontant()>$b->getMontant();});
        # usort($donations,function ($a,$b){return $a->getDateDon()>$b->getDateDon();});

        $teams = $entityManager->getRepository(Team::class)->findAll();
        usort($teams, function($a, $b){return $a->getWallet() > $b->getWallet();});

        return $this->render('donation/stats.html.twig', [
            'donations' =>$donations,
            'donation'=>$donation,
            'teams' => $teams


        ]);
    }


    /**
     * @Route("/new", name="app_donation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ourUser = $entityManager->getRepository(Joueur::class)->find($this->getUser());
            #$donation->setIduser($donation->getIduser()->getId());
            $donation->setIduser($ourUser);
            $entityManager->persist($donation);
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="create_donation" , methods={"POST"})
     */
    public function addDonation(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface, Request $request)
    {
        $content = $request->getContent();
        $donation = new Donation();
        $exemple = $entityManager->getRepository(Donation::class)->findAll();
        $data = explode("&", $content);
        $donation = clone $exemple[0];
        $donation->setMontant(explode("=", $data[2])[1]);
        $team = $entityManager->getRepository(Team::class)->find(explode("=", $data[1])[1]);
        $donation->setTeam($team);
        
        $entityManager->persist($donation);
        $entityManager->flush();
        return new Response('Tournoi ajouté');
    }



    /**
     * @Route("/{id}/edit", name="app_donation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Donation $donation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donation/edit.html.twig', [
            'donation' => $donation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_donation_delete", methods={"POST"})
     */
    public function delete(Request $request, Donation $donation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
    }



        
}
