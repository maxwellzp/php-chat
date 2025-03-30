<?php

namespace App\DataFixtures;

use App\Entity\ChatRoom;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $chatroom = new Chatroom();
        $chatroom->setName('General');
        $chatroom->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($chatroom);

        $chatroom = new Chatroom();
        $chatroom->setName('Business');
        $chatroom->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($chatroom);

        $chatroom = new Chatroom();
        $chatroom->setName('Economy');
        $chatroom->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($chatroom);

        $message = new Message();
        $message->setContent('Lorem Ipsum is simply dummy text of the printing and typesetting industry.');
        $message->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message);

        $message = new Message();
        $message->setContent("Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.");
        $message->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message);

        $message = new Message();
        $message->setContent("The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.");
        $message->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message);


        $manager->flush();
    }
}
