<?php

namespace App\Services\Telegram;

final class AlgorithmSteps
{
    /**
     * @var string[]
     */
    private const STEPS_LIST = [
        'navigate_to_node' => \App\Services\Telegram\AlgorithmSteps\NavigateToNode::class,
        'navigate_back' => \App\Services\Telegram\AlgorithmSteps\NavigateToNode::class,
        'show_node_navigation' => \App\Services\Telegram\AlgorithmSteps\ShowNodeNavigation::class,
        'request_user_testimonial' => \App\Services\Telegram\AlgorithmSteps\RequestUserTestimonial::class,
        'store_user_testimonial' => \App\Services\Telegram\AlgorithmSteps\StoreUserTestimonial::class,
    ];

    /**
     * @return string[]
     */
    public static function getList(): array
    {
        return self::STEPS_LIST;
    }
}