<?php

namespace App\Observers\Telegram;

use App\Models\Telegram\Node;
use Illuminate\Support\Facades\Cache;

/**
 * Class NodeObserver
 * @package App\Observers\Telegram
 */
class NodeObserver
{
    /**
     * Handle the Node "created" event.
     *
     * @param \App\Models\Telegram\Node $node
     * @return void
     */
    public function saved(Node $node)
    {
        Cache::forget('telegram-nodes');
        Cache::forget("telegram-nodes-{$node->id}");
    }

    /**
     * Handle the Node "deleted" event.
     *
     * @param \App\Models\Telegram\Node $node
     * @return void
     */
    public function deleted(Node $node)
    {
        Cache::forget('telegram-nodes');
        Cache::forget("telegram-nodes-{$node->id}");
    }
}
