<?php

namespace App\Controller;

use App\Form\MailType;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    /**
     * @Route("/stats", name="stats")
     */
    public function stat(ReclamationRepository $recRep){
        $technical_issues = 0;
        $reportaplayer = 0;
        $other = 0;
        $reclamations = $recRep->findAll();
        $recID = [];
        $rectest= [];

        foreach ($reclamations as $reclamation){

            if ($reclamation->getObject() == "Technical issue")
                $technical_issues = $technical_issues + 1;
            if ($reclamation->getObject() == "Report a player")
                $reportaplayer = $reportaplayer+1;
            if ($reclamation->getObject() == "Other")
                $other = $other+1;

            //$recID[] = $reclamation->getObject();
           // $rectest[] = $reclamation->getNumeroTel();

        }

        return $this->render('reclamation/stats.html.twig',[
            //'recID' =>json_encode($recID),
            //'rectest'=>json_encode($rectest)
            'technical_issues' => json_encode($technical_issues),
            'reportaplayer' => json_encode($reportaplayer),
            'other' => json_encode($other),
        ]);


    }

    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus("Non Traité");
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus("Traité");
            $entityManager->flush();

            return $this->redirectToRoute('app_rec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/allReclamation/mobile", name="AllReclamationJSON" , methods={"GET", "POST"})
     */
    public function AllReclamationJSON(NormalizerInterface $Normalizer, ReclamationRepository $reclamationRepository)
    {
        $repository= $this->getDoctrine()->getRepository(Reclamation::class);
        $Reclamation = $reclamationRepository->findAll();
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize($Reclamation);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/addreclamation/mobile", name="AddReclamationJSON", methods={"GET", "POST"} )
     */
    public function AddReclamationJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em= $this->getDoctrine()->getManager();
        $Reclamation = new Reclamation();
        $Reclamation->setSujet($request->query->get('sujet'));
        $Reclamation->setDescription($request->query->get('description'));
        $Reclamation->setAttachement($request->query->get('attachement'));
        $Reclamation->setEmail($request->query->get('email'));
//        $Reclamation->setDate(new \DateTime('@'.strtotime('Now')));
        $Reclamation->setNumeroTel($request->query->get('numeroTel'));
        $Reclamation->setStatus("Non Traité");
        $Reclamation->setObject($request->query->get('object'));
        $em->persist($Reclamation);
        $em->flush();



        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("Ajout avec succès");
        return new JsonResponse($formatted);

    }
    //127.0.0.1:8000/reclamation/AddReclamationJSON?sujet=testttt&description=testtttt&attachement=62729f17e2837937933084.jpg&email=raed@gmail.com&numeroTel=22556633&object=Other


    /**
     * @Route("/updatereclamation/mobile{id}", name="UpdateReclamationJSON" , methods={"GET", "POST"})
     */
    public function UpdateReclamationJSON($id,Request $request,NormalizerInterface $Normalizer)
    {
        // $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $Reclamation->setSujet($request->get('sujet'));
        $Reclamation->setDescription($request->get('description'));
        $Reclamation->setAttachement($request->get('attachement'));
        $Reclamation->setEmail($request->get('email'));
//        $Reclamation->setDate(new \DateTime('@'.strtotime('Now')));
        $Reclamation->setNumeroTel($request->get('numeroTel'));
        $Reclamation->setStatus('Traité');
        $Reclamation->setObject($request->get('object'));
        $em->flush();
        $jsonContent = $Normalizer->normalize($Reclamation,'json',['groups'=>'post:read']);
        return new Response("Update successfully".json_encode($jsonContent));
    }

    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/delete/mobile/{id}", name="deleteReclamationJSON",methods={"GET", "POST"})
     */
    public function deleteReclamationJSON(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($Reclamation!=null ) {
            $em->remove($Reclamation);
            $em->flush();
            $jsonContent = $normalizer->normalize($Reclamation, 'json', ['groups' => 'reservation']);
            return new Response("La reclamation a été supprimée avec succées!" . json_encode($jsonContent));
        }else{
            return new JsonResponse("id reclamation invalide.");}
    }
}
