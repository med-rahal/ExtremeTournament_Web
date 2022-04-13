<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="app_team")
     */
    public function index(): Response
    {
        return $this->render('team/login.html.twig', [
            'controller_name' => 'TeamController',
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
}
