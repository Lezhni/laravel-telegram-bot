<?php

namespace App\Services\Telegram;

/**
 * Class BotCommands
 * @package App\Services\Telegram
 */
final class BotCommands
{
    /**
     * @var string[]
     */
    private const COMMANDS_LIST = [
        'start' => \App\Services\Telegram\Commands\Start::class,
    ];

    /**
     * @return string[]
     */
    public static function getList(): array
    {
        return self::COMMANDS_LIST;
    }
}