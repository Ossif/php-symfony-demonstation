<?php

namespace App\Controller;


use App\Repository\UserRepository;

use App\Repository\ArticleRepository;
use App\Entity\Article;

use App\Repository\DivisionRepository;
use App\Entity\Division;

use App\Repository\AdminRepository;
use App\Entity\Admin;

use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Entity\Comment;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/read/{id}', name: 'article_id')]
    public function index($id, ArticleRepository $repo, CommentRepository $commRepo, Request $request): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

//Оценка
        $userRate = 0;
        $rateArray = explode("||", $this->getUser()->getRatedArticles());
        for($i = 0; $i < count($rateArray); $i ++){
            if(intval(explode(":",$rateArray[$i])[0]) == $id){

                $userRate = intval(explode(":",$rateArray[$i])[1]);
                break;

            }
        }
//Оценка    

        $article = $repo->findOneById($id);

        $dateCreation = $article->GetDateCreation()->format('d-m-Y'); 
        $dateEdit = $article->GetDateEdit()->format('d-m-Y'); 

        $filewayArray = explode("||", $article->getFileway());

        $package = new Package(new EmptyVersionStrategy());
        $testStr = $package->getUrl('/Files/Icons/home.svg');

        //Комментарии
        $commArray = $commRepo->findByArticleId($article);

        $userComment = new Comment();
        $form = $this->createForm(CommentFormType::class, $userComment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($form->get('text')->getData() !== null){
                $userComment->setUserId($this->getUser());
                $userComment->setArticleId($article);
                $userComment->setText($form->get('text')->getData());
                $userComment->setFileway("");
                $userComment->setDate(new \DateTime());

                $commRepo->save($userComment, true);
                return $this->redirect($request->getUri());
            }
        }

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
            'isAunt' => $isAunt,
            'dateCreation' => $dateCreation,
            'dateEdit' => $dateEdit,
            'filewayArray' => $filewayArray,
            'testStr' => $testStr,
            'articleId' => $id,
            'commArray' => $commArray,
            'commForm' => $form->createView(),
            'userRate' => $userRate,
        ]);
    }


    #[Route('/article/search', name: 'article_search')]
    public function articleSearch(AdminRepository $aRepo, ArticleRepository $repo, CommentRepository $commRepo, Request $request): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        $isAdmin = $aRepo->findOneByUserId($this->getUser());

        $query = $request->query->get('q');

        $sResults = null;

        if($query != null){
            $sResults = $repo->searchByQuery($query);
        }

        return $this->render('home/indexSearch.html.twig', [
            'controller_name' => 'Home',
            'isAunt' => $isAunt,
            'user' => $this->getUser(),
            'isAdmin' => $isAdmin,
            'sResult' => $sResults,
        ]);
    }


    #[Route('/article/delete/{id}', name: 'article_delete')]
    public function deleteArticle($id, ArticleRepository $repo, DivisionRepository $divRepo, Request $request, CommentRepository $commRepo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $commArr = $commRepo->findArrByArticleId($repo->findOneById($id));

        for($i = 0; $i < count($commArr); $i ++){
            $commRepo->remove($commArr[$i], true);

        }

        $division = $repo->findOneById($id)->getDivisionId();
        $division->setArticleCount($division->getArticleCount() - 1);
        $divRepo->save($division, true);


        $repo-> remove($repo->findOneById($id), true);

        $request->getSession()->invalidate(); 

        return $this->redirect('/');
    }

    #[Route('/article/chooseDivision/{id}', name: 'article_choose_division')]
    public function chooseArticleDivision(int $id, DivisionRepository $repo, AdminRepository $aRepo): Response
    {

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $isAdmin = $aRepo->findOneByUserId($this->getUser());

        $divArray = $repo->findAll();

        $user = $this->getUser();

        return $this->render('division/index3.html.twig', [
            'controller_name' => 'ArticleController',
            'isAunt' => $isAunt,
            'divArray' => $divArray,
            'isAdmin' => $isAdmin,
            'articleId' => $id,
            'user' => $user,
        ]);
    }

    #[Route('/article/changeDivision/{articleId}/{divId}', name: 'article_change_division')]
    public function changeArticleDivision(int $articleId, int $divId, DivisionRepository $repo, AdminRepository $adminRepo,ArticleRepository $articleRepo): RedirectResponse
    {

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $isAdmin = $adminRepo->findOneByUserId($this->getUser());

        $article = $articleRepo->findOneById($articleId);


        $division = $article->getDivisionId();
        $division->setArticleCount($division->getArticleCount() - 1);
        $repo->save($division, true);


        $article->setDivisionId($repo->findOneById($divId));

        $articleRepo->save($article, true);

        $division = $repo->findOneById($divId);
        $count = $division->getArticleCount();
        $division->setArticleCount($count + 1);
        $repo->save($division, true);


        return $this->redirectToRoute('division');
    }

    #[Route('/article/rate/{id}/{rating}', name: 'article_rate')]
    public function rateArticle($id, $rating, ArticleRepository $repo, UserRepository $uRepo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $article = $repo->findOneById($id);

        $flag = 0;

        if($user->getRatedArticles() != null && $user->getRatedArticles() != ""){
            $rateArray = explode("||", $user->getRatedArticles());
            for($i = 0; $i < count($rateArray); $i ++){
                if($rateArray[$i] == "") continue;
                $arr = explode(":", $rateArray[$i]);
                if(intval($arr[0]) == $id){
                    if(intval($arr[1]) != $rating){
                        $rateArray[$i] = "";
                        $user->setRatedArticles(implode("||", $rateArray));
                        $uRepo->save($user, true);

                        
                        $article->setRating($article->getRating() + $rating);
                        $repo->save($article, true);
                        $flag = 1;
                        break;
                    }
                    else{
                        $flag = 2;
                    }
                }
            }
        }
        if($flag == 0){
            $rateStr = $user->getRatedArticles();
            $rateStr .= "||{$id}:{$rating}";
            $user->setRatedArticles($rateStr);
            $uRepo->save($user);

            $currRate = $article->getRating();

            $article->setRating($currRate + $rating);
            $repo->save($article, true);
        }

        $hrefWay =  '/article/read/';
        $hrefWay .= strval($id);

        return $this->redirect($hrefWay);
    }

}
