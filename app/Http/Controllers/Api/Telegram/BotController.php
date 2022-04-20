<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Http\Controllers\Controller;
use App\Services\Telegram\BotService;
use Throwable;

class BotController extends Controller
{
    private $botService;

    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }

    public function __invoke(string $token)
    {
        $tokenValidated = $this->botService->isValidToken($token);
        if (! $tokenValidated) {
            abort(401);
        }

        try {
            $this->botService->handle();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
