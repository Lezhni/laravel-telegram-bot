<?php

namespace App\Services\Telegram\AlgorithmSteps;

use App\Helpers\TelegramHelper;
use App\Models\Telegram\Node;
use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use App\Services\Telegram\Commands\Start;
use App\Services\Telegram\Storages\AlgorithmStepStorage;
use App\Services\Telegram\Storages\NavigationStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/**
 * Class NavigateBack
 * @package App\Services\Telegram\AlgorithmSteps
 */
class NavigateBack implements Step
{
    use AwareBotApi, AwareMessage;

    /**
     * @var string
     */
    public const BACK_LABEL = 'Назад';

    /**
     * @var \App\Helpers\TelegramHelper
     */
    private $helper;

    /**
     * NavigateBack constructor.
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

        $currentNodeId = NavigationStorage::getCurrentNode($chatId);
        if ($currentNodeId === null) {
            $this->backToMainMenu($chatId);
            return;
        }

        $parentNode = Node::with('childrenNodes')
            ->whereHas('childrenNodes', function (Builder $query) use ($currentNodeId) {
                return $query->where('id', $currentNodeId);
            })
            ->first();
        if (! $parentNode instanceof Node) {
            $this->backToMainMenu($chatId);
            return;
        }

        $linksKeyboard = null;
        $links = $parentNode->links;
        if (is_array($links) && count($links) > 0) {
            $formattedLinks = $this->helper->formatBotLinks($links);
            $linksKeyboard = new InlineKeyboardMarkup($formattedLinks);
        }

        $rawText = $parentNode->text;
        $messageText = $this->helper->prepareMessageText($rawText);
        if ($messageText !== null) {
            $this->botApi->sendMessage($chatId, $messageText, 'HTML', null, null, $linksKeyboard);
        }

        NavigationStorage::storeCurrentNode($chatId, $parentNode->id);

        $nextStep = App::make(ShowNodeNavigation::class);
        $nextStep->setBotApi($this->botApi);
        $nextStep->setMessage($this->message);
        $nextStep->setNodes($parentNode->childrenNodes);
        $nextStep->handle();
    }

    /**
     * @param int $chatId
     */
    private function backToMainMenu(int $chatId)
    {
        AlgorithmStepStorage::reset($chatId);
        NavigationStorage::reset($chatId);

        $nextStep = App::make(Start::class);
        $nextStep->setBotApi($this->botApi);
        $nextStep->setMessage($this->message);
        $nextStep->handle();
    }
}