<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramHelper;
use App\Models\Telegram\Node;
use App\Services\Telegram\AlgorithmSteps\NavigateToNode;
use App\Services\Telegram\AlgorithmSteps\RequestUserTestimonial;
use App\Services\Telegram\Storages\AlgorithmStepStorage;
use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use App\Services\Telegram\Storages\NavigationStorage;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

/**
 * Class Start
 * @package App\Services\Telegram\Commands
 */
class Start implements Command
{
    use AwareBotApi, AwareMessage;

    /**
     * @var \App\Helpers\TelegramHelper
     */
    private $helper;

    /**
     * Start constructor.
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
        AlgorithmStepStorage::reset($chatId);
        NavigationStorage::reset($chatId);

        $nodes = Node::where('parent_id', null)->get(['title']);
        $nodes = $nodes->push(['title' => RequestUserTestimonial::TESTIMONIAL_LABEL]);

        $formattedNavigation = $this->helper->createBotNavigation($nodes, false);
        $replyKeyboard = new ReplyKeyboardMarkup($formattedNavigation);

        $userName = $this->message->getChat()->getUsername();
        $messageText = "Приятно познакомиться, {$userName}! Я AcademyPoker Bot - твой личный помощник в любых покерных вопросах! Выбирай один из пунктов и получи ответ!";

        AlgorithmStepStorage::storeCurrentStep($chatId, NavigateToNode::class);
        $this->botApi->sendMessage($chatId, $messageText, null, null, null, $replyKeyboard);
    }
}