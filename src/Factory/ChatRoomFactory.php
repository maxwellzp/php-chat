<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ChatRoom;

class ChatRoomFactory
{
    public function create(string $roomName): ChatRoom
    {
        $chatRoom = new ChatRoom();
        $chatRoom->setName($roomName);
        $chatRoom->setCreatedAt(new \DateTimeImmutable());
        return $chatRoom;
    }
}