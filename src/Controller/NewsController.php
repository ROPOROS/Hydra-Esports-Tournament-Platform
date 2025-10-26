<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//require  '..\vendor\autoload.php';
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twilio\Rest\Client;


/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/ShowMobile", name="app-show-news-mobile", methods={"GET", "POST"})

     */
    public function AllNewsJSON(NormalizerInterface $Normalizer, NewsRepository $newsRepository)
    {
        $repository= $this->getDoctrine()->getRepository(News::class);
        $News = $newsRepository->findAll();
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize($News);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/addnews/mobile/{idJ}", name="AddNewsMobile",methods={"GET", "POST"})
     */
    public function Addnews(Request $request,NormalizerInterface $Normalizer,$idJ)
    {
        $em= $this->getDoctrine()->getManager();
        $News = new News();

        $News->setSujetN($request->query->get('sujetN'));
        $News->setText($request->query->get('text'));
        $News->setImage($request->get('image'));
        $News->setDateC(new \DateTime('@'.strtotime($request->get("date_c")."+ 1 day")));
        $News->setDateF(new \DateTime('@'.strtotime($request->get("date_f")."+ 1 day")));
        $News->setIdjeu($this->getDoctrine()->getRepository(Jeu::class)->find($idJ));

        //$idJeu= $request->query->get('Jeu');
        $em->persist($News);
        $em->flush();

        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("Ajout avec succès");
        return new JsonResponse($formatted);
    }
//http://127.0.0.1:8000/news/addnews/mobile/1?sujetN=text&text=text&image=logoE.jpg&date_c=2020-03-03&date_f=2020-06-06&idJeu=1
    /**
     * @Route("/updateNews/mobile/{id}/{idJ}", name="UpdateNewsMobile" , methods={"GET", "POST"})
     */
    public function UpdateNewsMobile($id,Request $request,NormalizerInterface $Normalizer,$idJ)
    {
        // $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $News = $this->getDoctrine()->getRepository(News::class)->find($id);
        $News->setSujetN($request->query->get('sujetN'));
        $News->setText($request->query->get('text'));
        $News->setImage($request->get('image'));
        $News->setDateC(new \DateTime('@'.strtotime($request->get("date_c")."+ 1 day")));
        $News->setDateF(new \DateTime('@'.strtotime($request->get("date_f")."+ 1 day")));
        $News->setIdjeu($this->getDoctrine()->getRepository(Jeu::class)->find($idJ));
        $em->flush();
        $serialize = new Serializer([new ObjectNormalizer()]);
        $formatted = $serialize->normalize("Update avec succès");
        return new JsonResponse($formatted);
    }
    //http://127.0.0.1:8000/news/updateNews/mobile/53/2?sujetN=event&text=text&image=logoF.jpg&date_c=2020-07-07&date_f=2020-07-07&idJeu=2

    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleteN/mobile/{id}", name="deleteNewsJSON",methods={"GET", "POST"})
     */
    public function deleteNewsJSON(Request $request,NormalizerInterface $normalizer,$id){
        $em = $this->getDoctrine()->getManager();
        $News = $em->getRepository(News::class)->find($id);
        if($News!=null ) {
            $em->remove($News);
            $em->flush();
            $jsonContent = $normalizer->normalize($News, 'json', ['groups' => 'reservation']);
            return new Response("News été supprimée avec succées!" . json_encode($jsonContent));
        }else{
            return new JsonResponse("id news invalide.");}
    }


    /**
     * @Route("/", name="app_news_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $news = $entityManager
            ->getRepository(News::class)
            ->findAll();

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/new", name="app_news_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($news);
            $entityManager->flush();

//
//// Your Account SID and Auth Token from twilio.com/console
//            $account_sid = 'AC99487286eeb3dcb0df64834f4c7d6b3c';
//            $auth_token = 'e2d2256fd5c830402859dc7a1f0848c3';
//// In production, these should be environment variables. E.g.:
//// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
//
//// A Twilio number you own with SMS capabilities
//            $twilio_number = "+19378585152";
//
//            $client = new Client($account_sid, $auth_token);
//            $client->messages->create(
//            // Where to send a text message (your cell phone?)
//                '+21624191425',
//                array(
//                    'from' => $twilio_number,
//                    'body' => 'News ADDED'
//                )
//            );


            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);




    }

    /**
     * @Route("/{id}", name="app_news_show", methods={"GET"})
     */
    public function show(News $news): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_news_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_news_delete", methods={"POST"})
     */
    public function delete(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $entityManager->remove($news);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
    }

}
