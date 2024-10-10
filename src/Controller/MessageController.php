<?php

namespace App\Controller;

use App\Repository\UserRepository;

use App\Repository\MessageRepository;
use App\Entity\Message;

use App\Websocket\MessageHandler;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message/{id}', name: 'app_message')]
    public function message($id, UserRepository $uRepo, MessageRepository $mRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user1 = $this->getUser();
        $user2 = $uRepo->findOneById($id);

        if($user2 == null){
            return $this->redirect("/");
        }

        $messageArray1 = $mRepo->findBySR($user1, $user2);
        $messageArray2 = $mRepo->findByRS($user1, $user2);

        //$messageArray3 = $messageArray1 + $messageArray2;

        $messageArray3 = array();

        for($i = 0; $i < count($messageArray1); $i ++){
            array_push($messageArray3, $messageArray1[$i]); 
        }

        for($i = 0; $i < count($messageArray2); $i ++){
            array_push($messageArray3, $messageArray2[$i]); 
        }

        $finalArray = array();

        for($i = 0; $i < count($messageArray3); $i ++){
            $min = 99999999999999999;

            for($j = 0;  $j < count($messageArray3); $j ++){
                if($messageArray3[$j] == null) continue;
                if($messageArray3[$j]->getId() < $min){
                    $min = $messageArray3[$j]->getId();
                }
            }

            for($j = 0;  $j < count($messageArray3); $j ++){
                if($messageArray3[$j] == null) continue;
                if($messageArray3[$j]->getId() == $min){
                    
                    array_push($finalArray, $messageArray3[$j]); 
                    $messageArray3[$j] = null;
                    break;
                }
            }
        }

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'user1' => $user1,
            'user2' => $user2,
            'messageArray' => $finalArray,
        ]);
    }
    #[Route('/message/save/{id}/{text}', name: 'save_message')]
    public function saveMessage($id, string $text, UserRepository $uRepo, MessageRepository $mRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user1 = $this->getUser();
        $user2 = $uRepo->findOneById($id);

        if($user2 == null){
            return $this->redirect("/");
        }

        $message = new Message();

        $message->setText($text);
        $message->setDatetime(new \DateTime());
        $message->setSenderId($user1);
        $message->setReceiverId($user2);
        $message->setFileway(null);

        $mRepo->save($message, true);


        return $this->redirect("/message/{$id}");
    }
}
