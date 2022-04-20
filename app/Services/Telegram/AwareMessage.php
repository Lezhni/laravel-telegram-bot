<?php

namespace App\Services\Telegram;

use TelegramBot\Api\Types\Message;

/**
 * Trait AwareMessage
 * @package App\Services\TelegramApi
 */
trait AwareMessage
{
    /**
     * @var \TelegramBot\Api\Types\Message
     */
    private $message;

    /**
     * @param \TelegramBot\Api\Types\Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }
}