<?php

namespace App\Command;

use App\Repository\ChatRoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'chat:modify-chat-room',
    description: 'Add a short description for your command',
)]
class ChatModifyChatRoomCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChatRoomRepository $chatRoomRepository,
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

        $generalRoom = $this->chatRoomRepository->findOneBy(['id' => '0195f055-d839-731a-b207-cfe99f1cfc60']);
        $generalRoom->setName('Company');
        $this->entityManager->persist($generalRoom);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
