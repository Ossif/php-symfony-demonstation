<?php

namespace App\Controller;

use App\Form\ArticleFormType;
use App\Form\DivisionCreationType;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use App\Repository\DivisionRepository;

use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleCreationController extends AbstractController
{
    #[Route('/article/creation', name: 'article_creation')]
    public function index(Request $request, AuthorRepository $authRepo, DivisionRepository $divRepo, ArticleRepository $articleRep): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $article -> setName($form->get('name')->getData());
                $article -> setText($form->get('text')->getData());
                $article -> setFileway("");
                $article -> setRating(0);
                $article -> setDateCreation(new \DateTime());
                $article -> setDateEdit(new \DateTime());
                $article -> setAuthorId($authRepo->findOneByUserField($this->getUser()));
                $article -> setDivisionId($divRepo->findOneById(13));
                $articleRep->save($article, true);


                $division = $divRepo->findOneById(13);

                $division->setArticleCount($division->getArticleCount() + 1);
                $divRepo->save($division, true);

                $str = "/article/read/";
                $str .= strval($article->getId());
                return $this->redirect($str);
        }

        return $this->render('article_creation/index.html.twig', [
            'controller_name' => 'ArticleCreationController',
            'isAunt' => $isAunt,
            'articleCreationForm' => $form->createView(),
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit($id, Request $request, AuthorRepository $authRepo, DivisionRepository $divRepo, ArticleRepository $articleRep): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $article = $articleRep->findOneById($id);

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $article -> setName($form->get('name')->getData());
                $article -> setText($form->get('text')->getData());
                $article -> setDateEdit(new \DateTime());
                $articleRep->save($article, true);

                $str = "/article/read/";
                $str .= strval($article->getId());
                return $this->redirect($str);
        }

        return $this->render('article_creation/index2.html.twig', [
            'controller_name' => 'ArticleCreationController',
            'isAunt' => $isAunt,
            'articleCreationForm' => $form->createView(),
            'name' => $article->getName(),
            'text' => $article->getText(),
        ]);
    }
    
}
