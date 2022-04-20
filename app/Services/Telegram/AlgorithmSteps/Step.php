<?php

namespace App\Services\Telegram\AlgorithmSteps;

/**
 * Interface Step
 * @package App\Services\Telegram\AlgorithmSteps
 */
interface Step
{
    /**
     * @return void
     */
    public function handle(): void;
}