<?php

namespace App\Services\Telegram;

use App\Services\Telegram\AlgorithmSteps\NavigateBack;
use App\Services\Telegram\AlgorithmSteps\RequestUserTestimonial;
use App\Services\Telegram\Commands\Start;
use App\Services\Telegram\Storages\AlgorithmStepStorage;
use Illuminate\Support\Facades\App;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

/**
 * Class BotService
 * @package App\Services\Telegram
 */
class BotService
{
    /**
     * @var \TelegramBot\Api\Client
     */
    private $botClient;

    /**
     * @var \TelegramBot\Api\BotApi
     */
    private $botApi;

    /**
     * @var string|null
     */
    private $token;

    /**
     * TelegramBotService constructor.
     * @param \TelegramBot\Api\Client $botClient
     * @param \TelegramBot\Api\BotApi $botApi
     */
    public function __construct(Client $botClient, BotApi $botApi)
    {
        $this->botClient = $botClient;
        $this->botApi = $botApi;
        $this->token = config('telegram.bot_token');
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isValidToken(string $token): bool
    {
        return $token === $this->token;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $this->registerBotCommands();
        $this->handleUserReply();
        $this->botClient->run();
    }

    /**
     *
     */
    private function registerBotCommands()
    {
        $commands = BotCommands::getList();
        foreach ($commands as $alias => $class) {
            $this->botClient->command($alias, function (Message $message) use ($class) {
                $command = App::make($class);
                $command->setBotApi($this->botApi);
                $command->setMessage($message);
                $command->handle();
            });
        }
    }

    /**
     * @throws \Throwable
     */
    private function handleUserReply()
    {
        $this->botClient->on(function (Update $update) {
            $message = $update->getMessage();
            $messageText = trim($message->getText());
            switch ($messageText) {
                case NavigateBack::BACK_LABEL:
                    $currentStep = NavigateBack::class; break;
                case RequestUserTestimonial::TESTIMONIAL_LABEL:
                    $currentStep = RequestUserTestimonial::class; break;
                default:
                    $chatId = $message->getChat()->getId();
                    $currentStep = AlgorithmStepStorage::getCurrentStep($chatId);
                    $currentStep = $currentStep ?? Start::class;
            }

            $currentStep = App::make($currentStep);
            $currentStep->setBotApi($this->botApi);
            $currentStep->setMessage($message);
            $currentStep->handle();
        }, function () {
            return true;
        });
    }
}