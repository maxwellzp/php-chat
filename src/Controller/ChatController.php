<?php

namespace App\Controller;

use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    #[Route('/', name: 'chat_index')]
    public function chat(
        Request $request,
        HubInterface $hub,
        EntityManagerInterface $em,
        LoggerInterface $logger,
    ): Response
    {
        $user = $this->getUser();
        $message = new Message();
        $chatrooms = $em->getRepository(Chatroom::class)->findAll();
        $messages = $em->getRepository(Message::class)->findAll();

        $form = $this->createForm(MessageType::class, $message);
        $emptyForm = clone $form; // Used to display an empty form after a POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTimeImmutable());
            $em->persist($message);
            $em->flush();

            $hub->publish(new Update(
                'chat',
                $this->renderView('chat/message.stream.html.twig', ['message' => $message]),
            ));
            $form = $emptyForm;
        }

        return $this->render('chat/index.html.twig', [
            'form' => $form,
            'chatrooms' => $chatrooms,
            'messages' => $messages,
        ]);
    }
}
