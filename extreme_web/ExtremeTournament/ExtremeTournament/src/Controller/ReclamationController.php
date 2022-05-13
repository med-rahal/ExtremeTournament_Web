<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Form\UpdateRecType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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

    public function addReclamation(Request $request , \Swift_Mailer $mailer): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $name=$user->getUsername();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if($form->isSubmitted() &&$form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $reclamation->setDateR(new \DateTime());
            $reclamation->setEtatR('nontraitée');
            $reclamation->setIdUser($user);
            $reclamation->setEmail($user->getEmail());
            $type = $form->get('type')->getData();
            $date = $reclamation->getDateR();
             $em->persist($reclamation);
             $em->flush();
            $message = (new \Swift_Message('Welcome To ExtremeTournament!'))
                    ->setFrom('xtreametournamnet@gmail.com')
                    ->setTo($user->getEmail());
            $img = $message->embed(\Swift_Image::fromPath('images/logo.png'));
            $message->setBody(
                    $this->renderView(
                        'reclamation/reclamationconfirmation.html.twig',
                        ['name' => $name ,'type'=>$type,'date'=>$date,'img'=> $img]
                    ),
                    'text/html'
            );
            $mailer->send($message);

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


    /**
     * @return Response
     * @Route("/sortedrec",name="sortrec")
     */

    public function sort(){
        $rec = $this->getDoctrine()->getRepository(Reclamation::class)->sortReclamations();
        return $this->render('dashboard/affichereclamation.html.twig',['reclamations'=>$rec]);
    }


    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/Allreclamations",name="allrecs")
     */


    public function ALLReclamations(Request $request,NormalizerInterface $normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations = $repository->findAll();
        $jsoncontent = $normalizer->normalize($reclamations,'json',['groups'=>'post:read']);
        return New Response(json_encode($jsoncontent));


    }


    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse|Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/addreclamationJSON/new",name="addreclamationJSON")
     */
    public function addReclamationJSON(Request $request,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new reclamation();
        $iduser=$request->query->get('id_user');
        $reclamation->setDescriptionR($request->get('description_r'));
        $reclamation->setType($request->get('type'));
        $reclamation->setEtatR($request->get('etat_r'));
        $reclamation->setEmail($request->get('email'));
        $reclamation->setDateR(new \DateTime());
        $reclamation->setIdUser($this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser));
        try{
            $em->persist($reclamation);
            $em->flush();
             $jsoncontent = $normalizer->normalize($reclamation,'json',['groups'=>'post:read']);
            return New JsonResponse($jsoncontent);
        }catch (\Exception $ex){
            return new Response("Exception",$ex->getMessage());
        }


    }

    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @param $id
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
         * @Route("/updatereclamationJSON/{id}",name="updatereclamationJSON")
     */

    public function updateReclamationJSON(Request $request,NormalizerInterface $normalizer,$id)
    {
        $em =  $this->getDoctrine()->getManager();
        $reclamation= $em->getRepository(Reclamation::class)->find($id);
        $iduser=$request->query->get('id_user');
        $reclamation->setDescriptionR($request->get('description_r'));
        $reclamation->setType($request->get('type'));
        $reclamation->setEtatR($request->get('etat_r'));
        $reclamation->setEmail($request->get('email'));
        $reclamation->setDateR( new \DateTime());
        $reclamation->setIdUser($this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser));
        $em->flush();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getIdUser();
        });
        $serializer = new \Symfony\Component\Serializer\Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reclamation);
        return New Response(json_encode($formatted));

    }

    /**
     * @param Request $request
     * @param $id
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/deleterecJSON/{id}",name="deleterecjson")
     */

    public function deleteReclamationJSON(Request $request,$id,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($reclamation);
        $em->flush();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getIdUser();
        });
        $serializer = new \Symfony\Component\Serializer\Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reclamation);
        return New Response(json_encode($formatted));


    }




}
