<?php

namespace App\Services\Telegram\AlgorithmSteps;

use App\Helpers\TelegramHelper;
use App\Models\Telegram\Node;
use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use App\Services\Telegram\Storages\NavigationStorage;
use Illuminate\Support\Facades\App;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/**
 * Class NavigateToNode
 * @package App\Services\Telegram\AlgorithmSteps
 */
class NavigateToNode implements Step
{
    use AwareBotApi, AwareMessage;

    /**
     * @var \App\Helpers\TelegramHelper
     */
    private TelegramHelper $helper;

    /**
     * NavigateToNode constructor.
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

        $nodeTitle = trim($this->message->getText());
        $selectedNode = Node::with('childrenNodes')->where('title', $nodeTitle)->first();
        if (! $selectedNode instanceof Node) {
            $this->botApi->sendMessage($chatId, 'Вы выбрали несуществующий пункт, попробуйте еще раз');
            return;
        }

        $linksKeyboard = null;
        $links = $selectedNode->links;
        if (is_array($links) && count($links) > 0) {
            $formattedLinks = $this->helper->formatBotLinks($links);
            $linksKeyboard = new InlineKeyboardMarkup($formattedLinks);
        }

        $rawText = $selectedNode->text;
        $messageText = $this->helper->prepareMessageText($rawText);
        if ($messageText !== null) {
            $this->botApi->sendMessage($chatId, $messageText, 'HTML', null, null, $linksKeyboard);
        }

        NavigationStorage::storeCurrentNode($chatId, $selectedNode->id);

        $nextStep = App::make(ShowNodeNavigation::class);
        $nextStep->setBotApi($this->botApi);
        $nextStep->setMessage($this->message);
        $nextStep->setNodes($selectedNode->childrenNodes);
        $nextStep->handle();
    }
}