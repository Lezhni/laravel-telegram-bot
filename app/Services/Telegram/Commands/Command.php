<?php

namespace App\Services\Telegram\Commands;

/**
 * Interface Command
 * @package App\Services\Telegram\Commands
 */
interface Command
{
    /**
     * @return void
     */
    public function handle(): void;
}