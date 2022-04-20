<?php

namespace App\Services\Telegram;

use TelegramBot\Api\BotApi;

/**
 * Trait AwareBotApi
 * @package App\Services\TelegramApi
 */
trait AwareBotApi
{
    /**
     * @var \TelegramBot\Api\BotApi
     */
    private $botApi;

    /**
     * @param \TelegramBot\Api\BotApi $botApi
     */
    public function setBotApi(BotApi $botApi)
    {
        $this->botApi = $botApi;
    }
}