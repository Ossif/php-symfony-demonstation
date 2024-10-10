<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;

use App\Entity\Admin;
use App\Repository\AdminRepository;

use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/makeadmin/{id}', name: 'makeadmin')]
    public function makeUserAdmin($id, UserRepository $uRepo, AdminRepository $aRepo): Response
    {
        $myuser = $this->getUser();

        if($aRepo->findOneByUserId($myuser) != null){

            $user = $uRepo->findOneById($id);

            if($aRepo->findOneByUserId($user) === null){
                $admin = new Admin();
                $admin->setUserId($user);
                $admin->setHireDate(new \DateTime());

                $aRepo->save($admin, true);
            }
        }
        return $this->redirect("/friends/search");
    }
    #[Route('/deleteadmin/{id}', name: 'deleteadmin')]
    public function deleteUserAdmin($id, UserRepository $uRepo, AdminRepository $aRepo): Response
    {
        $user = $uRepo->findOneById($id);

        if($aRepo->findOneByUserId($user) !== null){
            $admin = $aRepo->findOneByUserId($user);

            $aRepo->remove($admin, true);
        }

        return $this->redirect("/");
    }
}
