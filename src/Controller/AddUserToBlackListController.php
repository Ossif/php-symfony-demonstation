<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AdminRepository;

use App\Entity\BlackList;
use App\Repository\BlackListRepository;

use App\Form\BlackListFormType;

use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AddUserToBlackListController extends AbstractController
{
    #[Route('/blackList/add/{id}', name: 'blackList_add')]
    public function addToBlackList($id, UserRepository $uRepo, AdminRepository $aRepo, Request $request, BlackListRepository $blRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        if($blRepo->findOneByUser($uRepo->findOneById($id)) != null){
            return $this->redirect("/blackList");
        }

        $blackList = new BlackList();
        $form = $this->createForm(BlackListFormType::class, $blackList);  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blackList->setUserId($uRepo->findOneById($id));
            $blackList -> setAdminId($aRepo->findOneByUserId($user));
            $blackList -> setDatetimeBegin(new \DateTime());
            $blackList -> setReason($form->get('reason')->getData());
            $blackList -> setDatetimeEnd($form->get('datetimeEnd')->getData());
            
            $blRepo->save($blackList, true);

            return $this->redirect("/blackList");
        }

        return $this->render('add_user_to_black_list/index2.html.twig', [
            'controller_name' => 'AddUserToBlackListController',
            'isAunt' => $isAunt,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/blackList/search', name: 'blackList_search')]
    public function searchInBlackList(UserRepository $uRepo, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $query = $request->query->get('q');
        $sResults = null;
        if($query != null){
            $sResults = $uRepo->searchByQuery($query);

        }

        return $this->render('add_user_to_black_list/index.html.twig', [
            'controller_name' => 'AddUserToBlackListController',
            'isAunt' => $isAunt,
            'user' => $user,
            'sResults' => $sResults,
        ]); 
    }

    #[Route('/blackList', name: 'blackList')]
    public function BL(BlackListRepository $blRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $sResults = $blRepo->findAll();

        for($i = 0; $i < count($sResults); $i ++){
            if(new DateTime() < $sResults[$i]->getDatetimeEnd()){
                $sResult[$i] = null;
            }
        }

        return $this->render('add_user_to_black_list/indexMain.html.twig', [
            'controller_name' => 'AddUserToBlackListController',
            'isAunt' => $isAunt,
            'user' => $user,
            'sResults' => $sResults,
        ]); 
    }
}
