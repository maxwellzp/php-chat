<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Entity\User;

class MessageFactory
{
    public function create(User $user, ChatRoom $chatRoom, string $content): Message
    {
        $message = new Message();
        $message->setSentBy($user);
        $message->setChatRoom($chatRoom);
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());
        return $message;
    }
}
