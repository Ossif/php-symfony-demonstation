<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'app_ban')]
    public function index(): Response
    {
        return $this->render('ban/index.html.twig', [
            'controller_name' => 'BanController',
        ]);
    }
}
