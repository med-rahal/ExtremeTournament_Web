<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Membre;
use App\Entity\User;
use App\Form\AddMembreType;
use App\Form\EquipeType;
use App\Form\JointeamType;
use App\Notifications\CreationCompteNotification;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use function Sodium\add;


class TeamController extends AbstractController
{


    /**
     * @Route("/team", name="app_team")
     */
    public function index(): Response
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->findAll();

        return $this->render('team/allTeams.html.twig',["equipe" => $equipe]);
    }
    /**
     * @Route("/Myteam/{nom_equipe}", name="app_Myteam")
     */
    public function indexMyTeam($nom_equipe): Response
    {

        $equipe=$this->getDoctrine()->getRepository(Equipe::class)->findByNomEquipe($nom_equipe);
        $membre =$this->getDoctrine()->getRepository(Membre::class)->findEntitiesByString($nom_equipe);


        return $this->render('team/Myteam.html.twig', [
            'controller_name' => 'TeamController',
            'equipe'=>$equipe,
            'membre'=>$membre
        ]);

    }

    /**
     * @Route("/teamback", name="app_teamback")
     */
    public function indexback(): Response
    {
        return $this->render('team/teamback.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }

    /**
     * @Route("/listTeamfront", name="listTeamfront")
     */
    public function listequipefront()
    {

        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->findBy([
            'id_user' => '1' // you can pass the user id or a user object
        ]);

        return $this->render('team/index.html.twig',array("equipe" => $equipe));
    }
    /**
     * @Route("/deleteTeamfront/{id}", name="deleteTeamfront")
     */
    public function deleteTeamfront($id)
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($equipe);
        $em->flush();
        return $this->redirectToRoute("listTeamfront");
    }

    /**
     * @Route("/addteamfront", name="addteamfront")
     */
    public function addTeamfront(Request $request, MailerInterface $mailer):Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('equipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $equipe->setImage($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($equipe);
            $em->flush();
            $email = $email = (new TemplatedEmail())
                ->from('xtreametournamnet@gmail.com')
                ->to('aminebentayaa04@gmail.com')
                ->subject('ExtremeTournament Create Team')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('emails/NotifCreer.html.twig');

            $mailer->send($email);



            return $this->redirectToRoute('listTeamfront');
        }
        return $this->render('team/Addteamfront.html.twig', ['form' => $form->createView()]);
    }
    /**
     * update to back
     * @Route("UpdateTeamfront/{id}", name="UpdateTeamfront")
     */
    public function updateTeamfront(Request $request, $id)
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Team updated!');
            return $this->redirectToRoute("listTeamfront");
        }
        return $this->render("team/UpdateTeamfront.html.twig",array('form'=>$form->createView()));
    }


    /**
     * @Route("/listTeamBack", name="listTeamBack")
     */
    public function listequipeback()
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->findAll();
        return $this->render('team/teamback.html.twig',["equipe" => $equipe]);

    }


    /**
     * @Route("/addteamBack", name="addteamBack")
     */
    public function addTeam(Request $request)
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('equipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $equipe->setImage($newFilename);
            }

                $em = $this->getDoctrine()->getManager();
                $em->persist($equipe);
                $em->flush();
                return $this->redirectToRoute('listTeamBack');
            }
            return $this->render('team/Addteamback.html.twig', ['form' => $form->createView()]);
        }

    /**
     * @Route("/deleteTeam/{id}", name="deleteTeam")
     */
    public function deleteTeam($id)
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($equipe);
        $em->flush();
        return $this->redirectToRoute("listTeamBack");
    }


    /**
     * update to back
     * @Route("UpdateTeam/{id}", name="UpdateTeam")
     */
    public function updateTeam(Request $request, $id)
    {
        $equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("listTeamBack");
        }
        return $this->render("team/UpdateTeam.html.twig",array('form'=>$form->createView()));
    }

    /**
     * search by team
     * @Route("/SearchTeam", name="SearchTeam")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $equipe =  $em->getRepository(Equipe::class)->findEntitiesByString($requestString);
        if(!$equipe) {
            $result['equipe']['error'] = "Team Not found :( ";
        } else {
            $result['equipe'] = $this->getRealEntities($equipe);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($equipe)
    {
        foreach ($equipe as $equips) {
            $realEntities[$equips->getNomEquipe()] = [$equips->getImage(), $equips->getNomEquipe()];

        }
        return $realEntities;
    }

    /**
     * @Route("/jointeam/{id_user}", name="jointeam")
     */
    public function jointeam($id_user,MailerInterface $mailer,Request $request):Response
    {

       $user=$this->getDoctrine()->getRepository(User::class)->find($id_user);
        $form = $this->createForm(JointeamType::class, $user);
        $form->add('Join',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $email = $email = (new TemplatedEmail())
                ->from('mohamedbentayaa04@gmail.com')
                ->to($user->getEmail())
                ->subject('Join Request')
                ->html('<h2 style="color: #9c3428">'. $user->getUsername() . '</h2> <h3> Wants to join your team</h3>');


            $mailer->send($email);
            return $this->redirectToRoute('app_team');
        }
        return $this->render("team/Jointeam.html.twig",array('form'=>$form->createView()));
    }

    /**
     *@Route("/Addpart", name="Addpart")
     */
    public function addParticipant(Request $request):Response {


        $membre =new Membre();
        $form = $this->createForm(AddMembreType::class, $membre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();
          return $this->redirect('listTeamfront');
        }
        return $this->render("team/AddParticipant.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/deleteMembre/{id}", name="deleteMembre")
     */
    public function deleteMembre($id)
    {
        $membre = $this->getDoctrine()->getRepository(Membre::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($membre);
        $em->flush();
        return $this->redirectToRoute("listTeamfront");
    }

}
