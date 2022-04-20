<?php

namespace App\Providers;

use App\Helpers\TelegramHelper;
use App\Services\Telegram\AlgorithmSteps;
use App\Services\Telegram\BotCommands;
use App\Services\Telegram\BotService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

class TelegramServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function () {
            $token = config('telegram.bot_token');
            return new Client($token);
        });

        $this->app->singleton(BotApi::class, function () {
            $token = config('telegram.bot_token');
            return new BotApi($token);
        });

        $this->app->singleton(BotService::class);
        $this->app->singleton(TelegramHelper::class);

        foreach (BotCommands::getList() as $class) {
            $this->app->singleton($class);
        }

        foreach (AlgorithmSteps::getList() as $class) {
            $this->app->singleton($class);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        $dependencies = [
            Client::class,
            BotApi::class,
            BotService::class,
            TelegramHelper::class,
        ];

        $commands = BotCommands::getList();
        $commands = array_values($commands);

        $steps = AlgorithmSteps::getList();
        $steps = array_values($steps);

        return array_merge($dependencies, $commands, $steps);
    }
}
