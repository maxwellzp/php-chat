<?php

declare (strict_types = 1);

namespace App\Command;

use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ChatRoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

#[AsCommand(
    name: 'chat:create-message',
    description: 'Add a short description for your command',
)]
class ChatCreateMessageCommand extends Command
{
    public function __construct(
        private HubInterface $hub,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ChatRoomRepository $chatRoomRepository,
        private Environment $twig,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $faker = Factory::create();
        $messageText = $faker->text(50);

        $chatRoom = $this->chatRoomRepository->findOneBy(['name' => 'General']);
        if (!$chatRoom instanceof ChatRoom) {
            throw new \Exception('ChatRoom not found');
        }

        $user = $this->userRepository->findOneBy(['email' => 'chat-tester@gmail.com']);
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }

        $message = new Message();
        $message->setSentBy($user);
        $message->setChatRoom($chatRoom);
        $message->setContent($messageText);
        $message->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $htmlMessage = $this->twig->render('chat/message.stream.html.twig', [
            'message' => $message,
        ]);

        $io->success(sprintf('The message "%s" has been created', $message->getContent()));

        $this->hub->publish(new Update(
            'chat', $htmlMessage
        ));

        $io->success(sprintf('The message "%s" has been sent to Mercure', $messageText));

        return Command::SUCCESS;
    }
}
