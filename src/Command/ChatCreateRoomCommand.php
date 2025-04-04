<?php

declare (strict_types = 1);

namespace App\Command;

use App\Factory\ChatRoomFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'chat:create-room',
    description: 'Add a short description for your command',
)]
class ChatCreateRoomCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChatRoomFactory $chatRoomFactory,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('roomName', InputArgument::OPTIONAL, 'Chat room name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $roomName = $input->getArgument('roomName')?: "test-name";

        $chatRoom = $this->chatRoomFactory->create($roomName);
        $this->entityManager->persist($chatRoom);
        $this->entityManager->flush();

        $io->success(sprintf('The room "%s" has been created', $chatRoom->getName()));

        return Command::SUCCESS;
    }
}
