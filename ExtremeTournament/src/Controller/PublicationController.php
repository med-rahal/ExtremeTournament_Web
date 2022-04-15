<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PublicationController extends AbstractController
{
    /**
     * @Route("/publication", name="app_pubication")
     */
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }
    /**
     * @route("/listepub",name="listepub")
     */
    public function listepub()
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();

        $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();
        return $this->render('publication/afficheforum.html.twig', array('publications' => $publications,
            'commentaires'=>$commentaires));
    }

    /**
     * @route("/listepubF",name="listepubF")
     */
    public function listepubF()
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();

        $publications = $this->getDoctrine()->getRepository(Publication::class)->findAll();
        return $this->render('blog/index.html.twig', array('publications' => $publications,
            'commentaires'=>$commentaires));
    }


    /**
     *
     * @route("/listecomF{id}",name="listecomF")
     */
    public function listecommentsF2($id)
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findByid_publication($id);

      ;
        return $this->render('blog/comments.html.twig', array(
            'commentaires'=>$commentaires));
    }
    /**
     *  @route("/deletepub/{id}", name="d")
     */
    public function deletepub($id)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute("listepub");
    }

    /**
     *  @route("/deletecomment/{id}", name="dc")
     */
    public function deletecomment($id)
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute("listepub");
    }
    /**
     *  @route("/deletecommentF/{id}", name="dcF")
     */
    public function deletecommentF($id)
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute("listepubF");
    }

    /**
 * @route ("/updatepub/{id}" , name="updatep")
 */

    public function updatepub(Request $request,$id)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $form = $this->createForm(PublicationType::class, $publication);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()  ) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listepub');
        }
        return $this->render("publication/updateforum.html.twig",array('f'=>$form->createView()));
    }

    /**
     * @route ("/updatecomment/{id}" , name="updatecomment")
     */

    public function updatecomment(Request $request,$id)
    {
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaires);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listepub');
        }
        return $this->render("publication/updateforum.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/addpub", name="addpub")
     */
    public function addpub(Request $request)
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('listepub');
        }
        return $this->render("publication/addpub.html.twig",array('f'=>$form->createView()));
    }

    /**
     * @Route("/addcomment", name="addcomment")
     */
    public function addComment(Request $request)
    {
        $commentaires = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaires);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaires);
            $em->flush();
            return $this->redirectToRoute('listepubF');
        }
        return $this->render("publication/addcomment.html.twig",array('f'=>$form->createView()));
    }

    /**
     * @Route("/addpubF", name="addpubF")
     */
    public function addpubF(Request $request)
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('listepubF');
        }
        return $this->render("publication/addpub.html.twig",array('f'=>$form->createView()));
    }

    /**
     *  @route("/deletepubF/{id}", name="df")
     */
    public function deletepubF($id)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute("listepubF");
    }

    /**
     * @route ("/updatepubF/{id}" , name="updatepF")
     */

    public function updatepubF(Request $request,$id)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $form = $this->createForm(PublicationType::class, $publication);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listepubF');
        }
        return $this->render("publication/updateforum.html.twig",array('f'=>$form->createView()));
    }


}
