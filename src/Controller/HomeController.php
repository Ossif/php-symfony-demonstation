<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;

use App\Entity\BlackList;
use App\Repository\BlackListRepository;
use Symfony\Component\Validator\Constraints\DateTime;

use App\Repository\AdminRepository;
use App\Repository\ArticleRepository;
use App\Repository\DivisionRepository;

use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\SecurityBundle\Security;






use Dompdf\Dompdf;
use Dompdf\Options;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AdminRepository $aRepo, BlackListRepository $blRepo): Response
    {
        $isAunt = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        $isAdmin = $aRepo->findOneByUserId($this->getUser());

        if($blRepo->findOneByUser($this->getUser()) != null){
            $banned = $blRepo->findOneByUser($this->getUser());
            $dt = date("Y-m-d H:i:s");

            $diff = ( strtotime($dt) - strtotime($banned->getDatetimeEnd()->format("Y-m-d H:i:s")) );

            if($diff < 0){
                return $this->redirect("/logout");
                /*return $this->render('ban/index.html.twig', [
                    'controller_name' => 'Home',
                    'isAunt' => $isAunt,
                    'user' => $this->getUser(),
                    'isAdmin' => $isAdmin,
                    'diff' => -1*( strtotime($dt) - strtotime($banned->getDatetimeEnd()->format("Y-m-d H:i:s")) ),
                ]);*/
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'isAunt' => $isAunt,
            'user' => $this->getUser(),
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/deleteacc', name: 'deleteacc')]
    public function deleteMyUser(UserRepository $repo, Request $request):RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        
        #Конец сессии
        $this->container->get('security.token_storage')->setToken(NULL);
        $request->getSession()->invalidate(); 

        $repo->remove($user, true);



        return $this->redirectToRoute('home');
    }


    #[Route('/makeReport', name: 'report')]
    public function makeReport(UserRepository $uRepo, 
        AdminRepository $aRepo,Request $request, BlackListRepository $blRepo,
        ArticleRepository $articleRepo, DivisionRepository $divRepo):Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        

        $blackList1 = $blRepo->findAll();
        $blackList2 = array();

        $dt = date("Y-m-d H:i:s");
        for($i = 0; $i < count($blackList1) ; $i ++){
            $diff = ( strtotime($dt) - strtotime($blackList1[$i]->getDatetimeEnd()->format("Y-m-d H:i:s")) );
            if($diff < 0){
                array_push($blackList2, $blackList1[$i]);
            }

        }

        $abl0 = $articleRepo->findAll();
        $abl = array();

        for($i = 0; $i < count($abl0) ; $i ++){
            if($abl0[$i]->getDivisionId()->getId() == 17){
                array_push($abl, $abl0[$i]);
            }
        }

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('documents/mypdf.html.twig', [
            'title' => "report",
            'users' => $uRepo->findAll(),
            'usersCount' => count($uRepo->findAll()),
            'admins' => $aRepo->findAll(),
            'adminsCount' => count($aRepo->findAll()),
            'bl' => $blackList2,
            'blCount' => count($blackList2),
            'articles' => $articleRepo->findAll(),
            'articlesCount' => count($articleRepo->findAll()),
            'divisions' => $divRepo->findAll(),
            'divisionsCount' => count($divRepo->findAll()),
            'abl' => $abl,
            'ablCount' => count($abl),
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

        return $this->redirect("/");
        /*return $this->render('documents/mypdf.html.twig', [

        ]);*/
    }

}
