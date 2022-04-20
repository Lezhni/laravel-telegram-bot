<?php

namespace App\Services\Telegram\Storages;

use Illuminate\Support\Facades\Cache;

/**
 * Class AlgorithmStepStorage
 * @package App\Services\Telegram\Storages;
 */
final class AlgorithmStepStorage
{
    /**
     * @param int $telegramUserId
     * @param string $stepClass
     */
    public static function storeCurrentStep(int $telegramUserId, string $stepClass)
    {
        Cache::put("{$telegramUserId}_current_step", $stepClass);
    }

    /**
     * @param int $telegramUserId
     * @return string|null
     */
    public static function getCurrentStep(int $telegramUserId): ?string
    {
        return Cache::get("{$telegramUserId}_current_step");
    }

    /**
     * @param int $telegramUserId
     */
    public static function reset(int $telegramUserId)
    {
        Cache::forget("{$telegramUserId}_current_step");
    }
}