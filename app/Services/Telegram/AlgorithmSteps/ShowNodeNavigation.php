<?php

namespace App\Services\Telegram\AlgorithmSteps;

use App\Helpers\TelegramHelper;
use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use Illuminate\Database\Eloquent\Collection;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

/**
 * Class ShowNodeNavigation
 * @package App\Services\Telegram\AlgorithmSteps
 */
class ShowNodeNavigation implements Step
{
    use AwareBotApi, AwareMessage;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private Collection $nodes;

    /**
     * @var \App\Helpers\TelegramHelper
     */
    private TelegramHelper $helper;

    /**
     * ShowNodeNavigation constructor.
     * @param \App\Helpers\TelegramHelper $helper
     */
    public function __construct(TelegramHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return void
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function handle(): void
    {
        $chatId = $this->message->getChat()->getId();

        $formattedNavigation = $this->helper->createBotNavigation($this->nodes);
        $replyKeyboard = new ReplyKeyboardMarkup($formattedNavigation);

        $messageText = 'Выберите пункт меню из списка:';
        $this->botApi->sendMessage($chatId, $messageText, null, null, null, $replyKeyboard);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $nodes
     */
    public function setNodes(Collection $nodes): void
    {
        $this->nodes = $nodes;
    }
}