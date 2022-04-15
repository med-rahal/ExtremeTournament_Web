<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\UpdateRecType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    /**
     * @param ReclamationRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/reclamation/update/{id}",name="updatereclamation")
     */

    public function update(ReclamationRepository $repository,$id,Request $request){
        $reclamation=$repository->find($id);
        $form = $this->createForm(UpdateRecType::class,$reclamation);
        $form->add('update',SubmitType::class);
       $form->handleRequest($request);

        if($form->isSubmitted() &&$form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_reclamationback');
        }
        return $this->render('reclamation/modify_reclamation.html.twig',['formrec'=>$form->createView()]);

    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/reclamation/delete/{id}",name="deletereclamation")
     */

    public function delete($id){
        $user =$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_reclamationback');


    }




}
