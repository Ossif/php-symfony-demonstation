<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Repository\FriendRepository;

use App\Repository\UserRepository;
use App\Repository\AdminRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ListOfFriendsController extends AbstractController
{
    #[Route('/friends/list', name: 'friends')]
    public function index(FriendRepository $fRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $outcomingArray = $fRepo -> findByOutcoming($user);

        for($i = 0; $i < count($outcomingArray); $i ++){
            if($fRepo -> findOneByOI($outcomingArray[$i]->getIncoming(), $user) != null){}
            else{
                $outcomingArray[$i] = null;
            }

        }

        return $this->render('list_of_friends/index.html.twig', [
            'controller_name' => 'ListOfFriendsController',
            'isAunt' => $isAunt,
            'user' => $user,
            'outcomingArray' => $outcomingArray,
        ]);
    }


    #[Route('/friends/outcoming', name: 'friends_outcoming')]
    public function friends_outcoming(FriendRepository $fRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $outcomingArray = $fRepo -> findByOutcoming($user);

        for($i = 0; $i < count($outcomingArray); $i ++){
            if($fRepo -> findOneByOI($outcomingArray[$i]->getIncoming(), $user) == null){}
            else{
                $outcomingArray[$i] = null;
            }

        }

        return $this->render('list_of_friends/index2.html.twig', [
            'controller_name' => 'ListOfFriendsController',
            'isAunt' => $isAunt,
            'user' => $user,
            'outcomingArray' => $outcomingArray,
        ]);
    }

    #[Route('/friends/incoming', name: 'friends_incoming')]
    public function friends_incoming(FriendRepository $fRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $incomingArray = $fRepo -> findByIncoming($user);

        for($i = 0; $i < count($incomingArray); $i ++){
            if($fRepo -> findOneByOI($user, $incomingArray[$i]->getOutcoming()) == null){}
            else{
                $incomingArray[$i] = null;
            }

        }

        return $this->render('list_of_friends/index3.html.twig', [
            'controller_name' => 'ListOfFriendsController',
            'isAunt' => $isAunt,
            'user' => $user,
            'outcomingArray' => $incomingArray,
        ]);
    }

    #[Route('/friends/search', name: 'friends_search')]
    public function friends_search_curr( FriendRepository $fRepo, UserRepository $uRepo, Request $request, AdminRepository $aRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');


        $user = $this->getUser();

        $isAdmin = $aRepo->findOneByUserId($user);

        $query = $request->query->get('q');

        $sResults = null;

        if($query != null){
            $sResults = $uRepo->searchByQuery($query);
        }

        return $this->render('list_of_friends/index4.html.twig', [
            'controller_name' => 'ListOfFriendsController',
            'isAunt' => $isAunt,
            'user' => $user,
            'sResults' => $sResults,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/friends/add/{friendid}', name: 'friends_add')]
    public function friends_add($friendid, FriendRepository $fRepo, UserRepository $uRepo, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');


        $user = $this->getUser();

        if($fRepo->findOneByOI($user, $uRepo->findOneById($friendid)) == null){
            $friend = new Friend();
            $friend -> setOutcoming($user);
            $friend -> setIncoming($uRepo->findOneById($friendid));

            $fRepo->save($friend, true);
        }

        return $this->redirect("/friends/list");
    }


    #[Route('/friends/delete/{friendid}', name: 'friends_delete')]
    public function friends_delete($friendid, FriendRepository $fRepo, UserRepository $uRepo, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');


        $user = $this->getUser();

        if($fRepo->findOneByOI($user, $uRepo->findOneById($friendid)) != null){

            $fRepo->remove($fRepo->findOneByOI($user, $uRepo->findOneById($friendid)), true);
        }

        return $this->redirect("/friends/list");
    }

    #[Route('/friends/make', name: 'friends_make')]
    public function makeFriend(FriendRepository $fRepo, UserRepository $uRepo): Response
    {
        /*$friend1 = new Friend();
        $friend1 -> setIncoming($uRepo -> findOneById(15));
        $friend1 -> setOutcoming($uRepo -> findOneById(16));
        $fRepo->save($friend1, true);

        $friend2 = new Friend();
        $friend2 -> setIncoming($uRepo -> findOneById(16));
        $friend2 -> setOutcoming($uRepo -> findOneById(15));
        $fRepo->save($friend2, true);*/

        $friend2 = new Friend();
        $friend2 -> setIncoming($uRepo -> findOneById(15));
        $friend2 -> setOutcoming($uRepo -> findOneById(5));
        $fRepo->save($friend2, true);

        return $this->redirect("/");
    }
}
