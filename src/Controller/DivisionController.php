<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;

use App\Repository\DivisionRepository;
use App\Entity\Division;

use App\Repository\ArticleRepository;
use App\Entity\Article;

use App\Repository\UserRepository;
use App\Entity\User;

use App\Repository\AdminRepository;
use App\Entity\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DivisionController extends AbstractController
{
    #[Route('/division', name: 'division')]
    public function index(DivisionRepository $repo, AdminRepository $aRepo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $isAdmin = $aRepo->findOneByUserId($this->getUser());

        $divArray = $repo->findAll();

        $user = $this->getUser();

        return $this->render('division/index1.html.twig', [
            'controller_name' => 'DivisionController',
            'isAunt' => $isAunt,
            'divArray' => $divArray,
            'isAdmin' => $isAdmin,
            'user' => $user,
        ]);
    }

    #[Route('/division/{id}', name: 'division_id')]
    public function goToCurrDivision($id, ArticleRepository $artRepo, DivisionRepository $divRepo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        
        $div = $divRepo->findOneById($id);

        $user = $this->getUser();

        $articleArray = $artRepo->findByDivisionId($div);

        return $this->render('division/index2.html.twig', [
            'controller_name' => 'DivisionController',
            'isAunt' => $isAunt,
            'articleArray' => $articleArray,
            'user' => $user,
        ]);
    }


    #[Route('/division/delete/{id}', name: 'division_del')]
    public function divisionDelete($id, DivisionRepository $divRepo): RedirectResponse
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        
        $divRepo->remove($divRepo->findOneById($id), true);

        return $this->redirectToRoute('division');
    }


    #[Route('/makeauthor', name: 'makeAuthor')]
    public function makeMeAuthor(AuthorRepository $repo): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        //Логика добавления в Authors новой строки

        if($repo->findOneByUserField($user) === null){
            //return $this->redirectToRoute('division');
            $author = new Author();
            $author->setUserId($user);

            $repo->save($author, true);
        }  
        

        return $this->redirectToRoute('article_creation');
    }
}
