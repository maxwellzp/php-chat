<?php

namespace App\DataFixtures;

use App\Entity\ChatRoom;
use App\Factory\MessageFactory;
use App\Factory\UserFactory;
use App\Repository\ChatRoomRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private ChatRoomRepository $chatRoomRepository,
        private MessageFactory $messageFactory,
        private UserFactory $userFactory
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $chatRoomNames = ['General', 'Business', 'Economy', 'Company'];

        foreach ($chatRoomNames as $chatRoomName) {
            $chatroom = new Chatroom();
            $chatroom->setName($chatRoomName);
            $chatroom->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($chatroom);
        }
        $manager->flush();

        $faker = Factory::create();
        $plainPassword = '123456';

        $user = $this->userFactory->create('chat-tester@gmail.com', $plainPassword, true);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

        $manager->persist($user);

        $generalGroup = $this->chatRoomRepository->findOneBy(['name' => 'General']);
        for ($i = 0; $i < 5; $i++) {
            $message = $this->messageFactory->create($user, $generalGroup, $faker->text(50));
            $manager->persist($message);
        }

        $manager->flush();
    }
}
