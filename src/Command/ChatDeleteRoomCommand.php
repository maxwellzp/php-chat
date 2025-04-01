<?php

declare (strict_types = 1);

namespace App\Command;

use App\Entity\ChatRoom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'chat:delete-room',
    description: 'Add a short description for your command',
)]
class ChatDeleteRoomCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('roomId', InputArgument::REQUIRED, 'Chat room id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $roomId = $input->getArgument('roomId');

        $chatRoom = $this
            ->entityManager
            ->getRepository(ChatRoom::class)
            ->find($roomId);

        if (!$chatRoom instanceof ChatRoom) {
            throw new \Exception('ChatRoom not found');
        }

        $this->entityManager->remove($chatRoom);
        $this->entityManager->flush();

        $io->success(sprintf('The room "%s" has been deleted', $roomId));
        return Command::SUCCESS;
    }
}
