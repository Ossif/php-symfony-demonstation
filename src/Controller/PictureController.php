<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Image;
use App\Form\ImageType;
use Symfony\Flex\Path;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PictureController extends AbstractController
{
    #[Route('/picture', name: 'app_picture')]
    public function index(): Response
    {
        return $this->render('picture/index.html.twig', [
            'controller_name' => 'PictureController',
        ]);
    }

  
  #[Route("/image/create", name:"create_image")]
 
    public function createImage(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();


        $Image = new Image();
        $imageForm = $this->createForm(ImageType::class, $Image);
    
        $imageForm->handleRequest($request);
    
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
    
            $Image = $imageForm->getData();
    
            $imageFile = $imageForm['image_file']->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
    
                // чтобы безопасно использовать имя файла как часть URL преобразуем его
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                // перемещаем файл в нужную директорию             
                $currentDatePath = (new \DateTime('now'))->format('Y/m/d');
                $folderPath = $this->getParameter('images_directory') . $currentDatePath;
    
                try {
                    $imageFile->move(
                        $folderPath,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // обрабатываем исключение на случай если что-то пошло не так
                }
    
                //
                //$Image->setFilePath($currentDatePath . $newFilename);
            }
    
            $entityManager->persist($Image);
            $entityManager->flush();
    
        }
    
        return $this->render('picture/index.html.twig', [
                'imageForm' => $imageForm->createView(),
        ]);
    }
}
