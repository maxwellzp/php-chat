<?php

namespace App\Command;

use App\Entity\ChatRoom;
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
    public function __construct(private EntityManagerInterface $entityManager)
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

        $chatRoom = new ChatRoom();
        $chatRoom->setName("test-name");
        $chatRoom->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($chatRoom);
        $this->entityManager->flush();


        $io->success(sprintf('The room "%s" has been created', $chatRoom->getName()));

        return Command::SUCCESS;
    }
}
