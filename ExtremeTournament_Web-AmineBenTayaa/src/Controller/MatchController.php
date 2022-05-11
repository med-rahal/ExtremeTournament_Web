<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Form\MatchsType;
use App\Repository\MatchsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match", name="app_match")
     */
    public function index(): Response
    {
        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }


    /**
     * @Route("/matchback", name="app_matchback")
     */
    public function indexback(): Response
    {
        return $this->render('match/matchback.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }
    /**
     * @Route("/listMatchBack", name="listMatchBack")
     */
    public function listMatch()
    {
        $matchs = $this->getDoctrine()->getRepository(Matchs::class)->findAll();
        return $this->render('match/matchback.html.twig', ["matchs" => $matchs]);
    }

    /**
     * @Route("/addmatch", name="addmatch")
     */
    public function addMatch(Request $request)
    {
        $matchs = new Matchs();
        $form = $this->createForm(MatchsType::class, $matchs);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($matchs);
            $em->flush();
            return $this->redirectToRoute('listMatchBack');
        }
        return $this->render("match/addmatch.html.twig",array('ff'=>$form->createView()));
    }

    /**
     * @Route("/deleteMatch/{id}", name="deleteMatch")
     */
    public function deleteMatch($id)
    {
        $matchs = $this->getDoctrine()->getRepository(Matchs::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($matchs);
        $em->flush();
        return $this->redirectToRoute("listMatchBack");
    }

    /**
     * update to back
     * @Route("Updatematch/{id}", name="Updatematch")
     */
    public function updateMatch(Request $request, $id)
    {
        $matchs = $this->getDoctrine()->getRepository(Matchs::class)->find($id);
        $form = $this->createForm(MatchsType::class, $matchs);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("listMatchBack");
        }
        return $this->render("match/Updatematch.html.twig",array('ff'=>$form->createView()));
    }


    /**
     * @Route ("stats" , name="stats")
     */
    public function statistiques(MatchsRepository $matchsRepo){

        $matchs =$matchsRepo->countBylocation();

        $matchEmp = [] ;
        $matchCount = [] ;
        foreach ($matchs as $match){
            $matchEmp[] = $match['emp'];
            $matchCount[] = $match['count'];
        }
        // On va chercher le nombre d'annonces publiées par date
        $annonces = $matchsRepo->countByDate();

        $dates = [];
        $annoncesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($annonces as $annonce) {
            $dates[] = $annonce['dateAnnonces'];
            $annoncesCount[] = $annonce['count'];
        }

    return $this->render('match/stats.html.twig', [
     'matchEmp'=> json_encode($matchEmp),
    'matchCount'=> json_encode($matchCount),
        'dates'=> json_encode($dates),
        'annoncesCount'=> json_encode($annoncesCount)
    ]);

    }

    /**
     * @Route("/listCalendar", name="listCalendar")
     */
    public function listcalendar(MatchsRepository $calendar)
    {
        $events =$calendar->findAll();
        foreach ($events as $event){
            $rdvs[]=[

                'title'=>$event->getNomEquipeB() . "\n VS \n" . $event->getNomEquipeA(),

                'start'=>$event->getDateMatch()->format('Y-m-d'),
                'description'=>"best match",
                'color'=>'green',


            ];
        }
        $data = json_encode($rdvs);
        return $this->render('match/Calendar.html.twig', compact('data'));
    }
}
