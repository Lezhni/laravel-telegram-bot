<?php

namespace App\Helpers;

use App\Services\Telegram\AlgorithmSteps\NavigateBack;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TelegramHelper
 * @package App\Helpers
 */
final class TelegramHelper
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection $nodes
     * @param bool $addBackButton
     * @return array
     */
    public function createBotNavigation(Collection $nodes, bool $addBackButton = true): array
    {
        if ($addBackButton) {
            $nodes = $nodes->push(['title' => NavigateBack::BACK_LABEL]);
        }

        return $nodes
            ->pluck('title')
            ->chunk(3)
            ->map(function ($chunk) { return $chunk->values(); })
            ->toArray();
    }

    /**
     * @param array $links
     * @return array[]
     */
    public function formatBotLinks(array $links): array
    {
        $formattedLinks = array_map(function ($link) {
            return [
                'text' => $link['label'] ?? $link['link'],
                'url' => $link['link'],
            ];
        }, $links);

        return array_chunk($formattedLinks, 3);
    }

    /**
     * @param string|null $rawText
     * @return string|null
     */
    public function prepareMessageText($rawText): ?string
    {
        $text = trim($rawText);
        if ($text == null || empty($text)) {
            return null;
        }

        return strip_tags($text, '<b><i><u><s><strong><em><ins><del><a>');
    }
}