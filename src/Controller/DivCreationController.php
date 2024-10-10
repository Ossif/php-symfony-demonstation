<?php

namespace App\Controller;

use App\Form\DivisionCreationType;
use App\Entity\Division;
use App\Repository\DivisionRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DivCreationController extends AbstractController
{
    #[Route('/division/creation', name: 'division_create')]
    public function index(Request $request, DivisionRepository $repo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');


        $error = 0;
        $division = new Division();
        $form = $this->createForm(DivisionCreationType::class, $division);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($repo->findOneByName($form->get('name')->getData()) === null){
                $division -> setName($form->get('name')->getData());
                $division -> setArticleCount(0);

                $repo->save($division, true);
                $error = 2;
                return $this->redirectToRoute('division');
            }
            else{
                $error = 1;
            }
        }

        return $this->render('div_creation/index.html.twig', [
            'controller_name' => 'DivCreationController',
            'divCreationForm' => $form->createView(),
            'error' => $error,
            'isAunt' => $isAunt,
        ]);
    }


    #[Route('/division/edit/{id}', name: 'division_edit')]
    public function edit($id, Request $request, DivisionRepository $repo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');


        $division = $repo->findOneById($id);

        $form = $this->createForm(DivisionCreationType::class, $division);
        $form->handleRequest($request);

        if($division == null){
            return $this->redirect("/");

        }

        $error = 0;


        if ($form->isSubmitted() && $form->isValid()) {

            $division -> setName($form->get('name')->getData());
            $division -> setArticleCount(0);

            $repo->save($division, true);
            $error = 2;
            return $this->redirectToRoute('division');
        }

        return $this->render('div_creation/index2.html.twig', [
            'controller_name' => 'DivCreationController',
            'divCreationForm' => $form->createView(),
            'error' => $error,
            'isAunt' => $isAunt,
        ]);
    }
}
