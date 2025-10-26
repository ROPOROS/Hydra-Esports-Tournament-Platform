<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/joueur")
 */
class JoueurController extends AbstractController
{
    /**
     * @Route("/", name="app_joueur_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $joueurs = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();

        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }
    /**
     * @Route ("/user/admiin/M", name="app_user_indexM")
     */
    public function indexM(NormalizerInterface $Normalizer)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Joueur::class)->findAll();
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($user);

        return new JsonResponse($formatted);

        /* $repository= $this->getDoctrine()->getRepository(User::class);
         $users= $repository->findAll();
         $jsonContent = $Normalizer->normalize($users, 'json');


         return new Response(json_encode($jsonContent));*/
    }
    /**
     * @Route("/user/new/M", name="app_user_newM")
     */
    public function newM(Request $request,NormalizerInterface $Normalizer)
    {
        $dateImmutable = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now')); # also tried using \DateTimeImmutable

        $user = new Joueur();
        $nom_user=$request->query->get("nom");
        //$nom_user= $request->query->get("nom");
        $prenom_user = $request->query->get("prenom");
        $email_user = $request->query->get("mail");
        $mdp_user = $request->query->get("mdp");
        $em= $this->getDoctrine()->getManager();
        $user->setPassword($mdp_user);

        $user->setNom($nom_user);
        $user->setPrenom($prenom_user);
        $user->setUsername($email_user);
        // $user->setMdpUser($mdp_user);
        $user->setAvertissement(0);
        $user->setType('joueur');
        $user->setWallet(0);
        $user->setPhoto('pic');
        $user->setDatenaissance(new \DateTime('05-04-2000'));




        $em->persist($user);
        $em->flush();

        $jsonContent = $Normalizer->normalize($user,'json');
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/user/M/{id_user}", name="app_user_showM", methods={"GET"})
     */
    public function showM( NormalizerInterface $Normalizer, $id_user): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Joueur::class)->find($id_user);
        $jsonContent = $Normalizer->normalize($user,'json');
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/user/edit/M", name="app_user_editM")
     */
    public function editM(Request $request): Response
    {
        $em= $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getManager()
            ->getRepository(Joueur::class)
            ->find($request->get("id"));
        $user->setNom($request->get("nom"));
        $user->setPrenom($request->get("prenom"));
        $user->setUsername($request->get("mail"));




        $em->persist($user);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize(($user));
        return new JsonResponse("users a ete modifiee avec success");




    }
    /**
     * @Route("/login/M/{mail}/{mdp}", name="app_user_loginM", methods={"GET", "POST"} )
     */
    public function SignInAction(Request $request, $mail, $mdp ){

//        $email_user= $request->query->get("mail");
//        $mdp_user= $request->query->get("mdp");

        $em=$this->getDoctrine()->getManager();
        $user= $em->getRepository(Joueur::class)->findOneBy(['mail'=>$mail]);
        if($user) {
            if ($mdp==$user->getPassword()) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);

            } else {
                return new Response("password not found");
            }
        }
        else{
            return new Response("user not found");
        }
    }





    /**
     * @Route("/liste", name="liste_joueurs")
     */
    public function getListeTournois(EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $joueurs = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();
        $json = $serializerInterface->serialize($joueurs, 'json');

        return new Response($json);
    }
    /**
     * @Route("/get/{id}", name="get_joueurIds", methods={"GET"})
     */
    public function getJoueurById(Joueur $joueur, EntityManagerInterface $entityManager, SerializerInterface $serializerInterface)
    {
        $joueurs = $entityManager
            ->getRepository(Joueur::class)
            ->findBy(['id'=>$joueur->getId()]);
        $json = $serializerInterface->serialize($joueurs, 'json');

        return new Response($json);
    }
    /**
     * @Route("/joueurFront", name="joueur_showf")
     */
    public function afficherJoueur ( EntityManagerInterface $entityManager)
    {
        $joueurs = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();

        return $this->render('joueur/joueurFront.html.twig', ['joueurs' => $joueurs,] );


    }
    /**
     * @Route("/joueurFrontinv", name="joueur_showf_invitation")
     */
    public function afficherJoueursauflogin ( EntityManagerInterface $entityManager)
    {
        $joueurs = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();

        return $this->render('invitation/listJoueur.html.twig', ['joueurs' => $joueurs,] );


    }

    /**
     * @Route("/new", name="app_joueur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           // $hash = $encoder->encodePassword($joueur, $joueur->getPassword() );
           // $joueur->setPassword($hash);
            $joueur->setAvertissement(0);
            $joueur->setWallet(0);
            $joueur->setType('joueur');
            $joueur->setPhoto('picture.png');
          // $joueur->setMail($joueur->getUsername() );


            $entityManager->persist($joueur );
            $entityManager->flush();

            return $this->redirectToRoute('security_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_joueur_avert", methods={"GET"})
     */
    public function show(Joueur $joueur, EntityManagerInterface $entityManager ): Response
    {
        $joueur->setAvertissement($joueur->getAvertissement()+1);
        $entityManager->flush();
        return $this->redirectToRoute('joueur_showf');
    }
    /**
     * @Route("/{id}/remove", name="app_user_remove", methods={"GET"})
     */
    public function showdel(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_joueur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }


  /*  public function login() : Response
    {
        return $this->render('joueur/login.html.twig');

    }*/

    /**
     * @Route("/{id}", name="app_joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joueur_showf', [], Response::HTTP_SEE_OTHER);
    }



}
