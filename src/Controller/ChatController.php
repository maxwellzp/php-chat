<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\ChatRoom;
use App\Entity\Message;

class ChatController extends AbstractController
{
    #[Route('/', name: 'chat_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $chatRooms = $em->getRepository(ChatRoom::class)->findAll();

        return $this->render('chat/index.html.twig', [
            'chatRooms' => $chatRooms
        ]);
    }

    #[Route('/chat/{id}', name: 'chat_room')]
    public function show(ChatRoom $chatRoom, EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)->findBy(
            ['chatRoom' => $chatRoom],
            ['createdAt' => 'ASC'],
            50,
        );

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, [
            'action' => $this->generateUrl('send_message', ['id' => $chatRoom->getId()]),
            'method' => 'POST',
        ]);

        return $this->render('chat/show.html.twig', [
            'chatRoom' => $chatRoom,
            'messages' => $messages,
            'form' => $form->createView(),
            'turboStream' => '/chat/' . $chatRoom->getId(),
        ]);
    }

    #[Route('/chat/{id}/send', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request, ChatRoom $chatRoom, EntityManagerInterface $em, HubInterface $hub): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setChatRoom($chatRoom);
            $message->setSentBy($this->getUser());
            $message->setCreatedAt(new \DateTimeImmutable());
            $em->persist($message);
            $em->flush();

            $update = new Update(
                '/chat/' . $chatRoom->getId(),
                $this->renderView('chat/_message.stream.html.twig', [
                    'message' => $message
                ])
            );

            $hub->publish($update);
        }

        return $this->redirectToRoute('chat_room', ['id' => $chatRoom->getId()]);
    }
}
