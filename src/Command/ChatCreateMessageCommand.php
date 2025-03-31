<?php

namespace App\Command;

use App\Entity\Message;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        private Environment $twig,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $faker = Factory::create();
        $messageText = $faker->text(50);

        $user = $this->userRepository->findOneBy(['email' => 'chat-tester@gmail.com']);

        $message = new Message();
        $message->setSentBy($user);
        $message->setContent($messageText);
        $message->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $htmlMessage = $this->twig->render('chat/message.stream.html.twig', [
            'message' => $message,
        ]);
        $io->write($htmlMessage);

        $io->success(sprintf('The message "%s" has been created', $message->getContent()));

        $this->hub->publish(new Update(
            'chat', $htmlMessage
        ));

        $io->success(sprintf('The message "%s" has been sent to Mercure', $messageText));

        return Command::SUCCESS;
    }
}
