<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifierUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/modifier",name="modify_user")
     */

    public function update(UserRepository $repository, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ModifierUserType::class, $user);
        $form->handleRequest($request);
        $file = $form->get('image')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

           // $user->setImage(new File($this->getParameter('brochures_directory').'/'.$user->getImage()));
            $user->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('user/modify_user.html.twig', ['formA' => $form->createView(),'img'=>$file]);


    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/user/{id}",name="suppuser")
     */

    public function delete(Request $request,TokenStorageInterface $tokenStorage,$id){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $this->get('security.token_storage')->setToken(null);
        $em->remove($user);
        $em->flush();
        $request->getSession()->invalidate();
        return $this->redirectToRoute('login_security');


    }




    /**
     * @Route("/listU", name="listU", methods={"GET"})
     */
    public function listU(UserRepository $rep) :Response
    {


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $reader=$rep->findAll();


        // Retrieve the HTML generated in our twig file

        $html = $this->renderView('dashboard/listU.html.twig', array(
            'users'=>$reader
        ));

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("UsersList.pdf", [
            "Attachment" => true
        ]);

        // Send some text response
        return new Response("The PDF file has been succesfully generated !");

    }









}