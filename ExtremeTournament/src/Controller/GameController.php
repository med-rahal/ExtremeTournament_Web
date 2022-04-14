<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="app_game")
     */
    public function index(): Response
    {
        return $this->render('game/SecondInterfaceADJOIN.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/gameback", name="appgameback")
     */
    public function indexback(): Response
    {
        $Tournoi = $this->getDoctrine()->getManager()->getRepository(Tournoi::class)->findAll();
        return $this->render('game/gameback.html.twig', [
            'T'=>$Tournoi
        ]);
    }

    /**
     * @Route("/AddGameBack", name="AddGameBack")
     */
    public function AddGameB(\Symfony\Component\HttpFoundation\Request $request): Response
    {
        $Tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $Tournoi);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isvalid()) {
        $em= $this->getDoctrine()->getManager();
        $em->persist($Tournoi);  // Add
        $em->flush();
        return $this->redirectToRoute('appgameback');
        }
        return $this->render('game/CreateGameB.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/RemoveGameB/{id_t}", name="RemoveGameBack")
     */
    public function RemoveGameB(Tournoi $Tournoi): Response
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($Tournoi);
        $em->flush();
        return $this->redirectToRoute('appgameback');
    }
    /**
     * @Route("/ModifyGameB/{id_t}", name="ModifyGameBack")
     */
    public function ModifyGameB(\Symfony\Component\HttpFoundation\Request $request , $id_t): Response
    {
        $Tournoi =$this->getDoctrine()->getManager()->getRepository(Tournoi::class)->find($id_t);
        $form = $this->createForm(TournoiType::class, $Tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('appgameback');
        }
        return $this->render('game/ModifyGameB.html.twig',['f'=>$form->createView()]);
    }

/** Code FRONTT  */



    /**
     * @Route("/gameFChoose", name="app_gameFChoose")
     */
    public function indexChooseS(): Response
    {
        return $this->render('game/SecondInterfaceADJOIN.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/gameFListJoin", name="app_gameFJoin")
     */
    public function indexJoin(): Response
    {

        $Tournoi = $this->getDoctrine()->getManager()->getRepository(Tournoi::class)->findAll();
        return $this->render('game/index.html.twig', [
            'T'=>$Tournoi
        ]);

    }

    /**
     * @Route("/gameFListJoinInterface", name="app_gameFJoinInterface")
     */
    public function indexJoinInterface(): Response
    {

        $Tournoi = $this->getDoctrine()->getManager()->getRepository(Tournoi::class)->findAll();
        return $this->render('game/TournamentExistInterface.html.twig', [
            'T'=>$Tournoi
        ]);
    }




    /**
     * @Route("/gameFront", name="app_gameFront")
     */
    public function indexfront(): Response
    {
        $Tournoi = $this->getDoctrine()->getManager()->getRepository(Tournoi::class)->findAll();
        return $this->render('game/gameFront.html.twig', [
            'T'=>$Tournoi
        ]);
    }


    /**
     * @Route("/AddGameF", name="AddGameFront")
     */
    public function AddGameF(\Symfony\Component\HttpFoundation\Request $request): Response
    {
        $Tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $Tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em= $this->getDoctrine()->getManager();
            $em->persist($Tournoi);  // Add
            $em->flush();
            return $this->redirectToRoute('app_gameFJoin');
        }
        return $this->render('game/CreateGameF.html.twig',['f'=>$form->createView()]);
    }



    /**
     * @Route("/RemoveGameF/{id_t}", name="RemoveGameFront")
     */
    public function RemoveGameF(Tournoi $Tournoi): Response
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($Tournoi);  // Add
        $em->flush();
        return $this->redirectToRoute('app_gameFront');
    }

    /**
     * @Route("/ModifyGameF/{id_t}", name="ModifyGameFront")
     */
    public function ModifyGameF(\Symfony\Component\HttpFoundation\Request $request , $id_t): Response
    {
        $Tournoi =$this->getDoctrine()->getManager()->getRepository(Tournoi::class)->find($id_t);
        $form = $this->createForm(TournoiType::class, $Tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gameFJoin');
        }
        return $this->render('game/ModifyGameF.html.twig',['f'=>$form->createView()]);
    }
    /**
     * @Route("/ModifyGameFF/{nomT}", name="ModifyGameFrontt")
     */
    public function ModifyGameFront(\Symfony\Component\HttpFoundation\Request $request, $nomT): Response
    {
        $Tournoi =$this->getDoctrine()->getManager()->getRepository(Tournoi::class)->find($nomT);
        $form = $this->createForm(TournoiType::class, $Tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_gameFJoin');
        }
        return $this->render('game/ModifyGameF.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/RemoveGameFF/{nomT}", name="RemoveGameFrontt")
     */
    public function RemoveGameFront(Tournoi $Tournoi): Response
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($Tournoi);
        $em->flush();
        return $this->redirectToRoute('app_gameFJoin');
    }


}
