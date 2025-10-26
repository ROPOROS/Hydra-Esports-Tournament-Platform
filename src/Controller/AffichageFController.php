<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class AffichageFController extends AbstractController
{
    /**
     * @Route("/newsF", name="app_affichage_f")
     */
    public function index(EntityManagerInterface $entityManager): Response

    {
        $news = $entityManager
            ->getRepository(News::class)
            ->findAll();
        return $this->render('affichage_f/AffichageF.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/{id}", name="app_news_show", methods={"GET"})
     */
    public function show(News $news): Response
    {
        return $this->render('affichage_f/show.html.twig', [
            'news' => $news,
        ]);
    }
}
