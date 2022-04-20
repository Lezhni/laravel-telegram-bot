<?php

namespace App\Services\Telegram\Storages;

use Illuminate\Support\Facades\Cache;

/**
 * Class NavigationStorage
 * @package App\Services\Telegram\Storages;
 */
final class NavigationStorage
{
    /**
     * @param int $telegramUserId
     * @param int $nodeId
     */
    public static function storeCurrentNode(int $telegramUserId, int $nodeId): void
    {
        Cache::put("{$telegramUserId}_current_node", $nodeId);
    }

    /**
     * @param int $telegramUserId
     * @return int|null
     */
    public static function getCurrentNode(int $telegramUserId): ?int
    {
        return Cache::get("{$telegramUserId}_current_node");
    }

    /**
     * @param int $telegramUserId
     */
    public static function reset(int $telegramUserId): void
    {
        Cache::forget("{$telegramUserId}_current_node");
    }
}