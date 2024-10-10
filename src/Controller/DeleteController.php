<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    #[Route('/delete', name: 'app_delete')]
    public function index(): Response
    {
        return $this->render('delete/index.html.twig', [
            'controller_name' => 'DeleteController',
        ]);
    }
}
