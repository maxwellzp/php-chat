<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'chat:create-user',
    description: 'Add a short description for your command',
)]
class ChatCreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager,
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

        $plainPassword = '123456';
        $email = $faker->email();

        $user = new User();
        $user->setPassword($plainPassword);
        $user->setEmail($email);
        $user->setIsVerified(true);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf(
            'The user (#%s) %s has been created with password = (%s)',
            $user->getId(), $email, $plainPassword
        ));

        return Command::SUCCESS;
    }
}
