<?php

namespace App\DataFixtures;

use App\Entity\ChatRoom;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
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

        for ($i = 0; $i < 5; $i++) {
            $message = new Message();
            $message->setContent($faker->text(50));
            $message->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($message);
        }

        $manager->flush();
    }
}
