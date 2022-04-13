<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="app_reclamation")
     */
    public function indexfront()
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @Route("/reclam", name="app_reclamationback")
     */
    public function affiche(): Response
    {

        $rep = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations=$rep->findAll();
        return $this->render('dashboard/affichereclamation.html.twig',['reclamations'=>$reclamations]);

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/addreclamation",name="add_reclamation")
     */

    public function addReclamation(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if($form->isSubmitted() &&$form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $reclamation->setDateR(new \DateTime());
            $reclamation->setEtatR('nontraitée');
            $reclamation->setIdUser($user);
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('reclamation/addreclamation.html.twig',['formR'=>$form->createView()]);

    }




}
