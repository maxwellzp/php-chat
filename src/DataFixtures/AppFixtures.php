<?php

namespace App\DataFixtures;

use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $chatRoomNames = ['General', 'Business', 'Economy'];

        foreach ($chatRoomNames as $chatRoomName) {
            $chatroom = new Chatroom();
            $chatroom->setName($chatRoomName);
            $chatroom->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($chatroom);
        }

        $faker = Factory::create();
        $plainPassword = '123456';

        $user = new User();
        $user->setPassword($plainPassword);
        $user->setEmail('chat-tester@gmail.com');
        $user->setIsVerified(true);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        $manager->persist($user);


        for ($i = 0; $i < 5; $i++) {
            $message = new Message();
            $message->setSentBy($user);
            $message->setContent($faker->text(50));
            $message->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($message);
        }

        $manager->flush();
    }
}
