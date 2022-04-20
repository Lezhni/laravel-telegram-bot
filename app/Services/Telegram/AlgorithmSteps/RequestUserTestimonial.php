<?php

namespace App\Services\Telegram\AlgorithmSteps;

use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use App\Services\Telegram\Storages\AlgorithmStepStorage;
use App\Services\Telegram\Storages\NavigationStorage;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

/**
 * Class RequestUserTestimonial
 * @package App\Services\Telegram\AlgorithmSteps
 */
class RequestUserTestimonial implements Step
{
    use AwareBotApi, AwareMessage;

    /**
     * @var string
     */
    public const TESTIMONIAL_LABEL = 'Оставить отзыв';

    /**
     * @return void
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function handle(): void
    {
        $chatId = $this->message->getChat()->getId();

        $replyKeyboard = new ReplyKeyboardMarkup([[NavigateBack::BACK_LABEL]]);
        $messageText = 'Твое мнение очень важно для нас! Пожалуйста, напиши свой отзыв ниже, или нажмите "Назад", чтобы вернуться в главное меню:';

        AlgorithmStepStorage::storeCurrentStep($chatId, StoreUserTestimonial::class);
        NavigationStorage::reset($chatId);

        $this->botApi->sendMessage($chatId, $messageText, null, null, null, $replyKeyboard);
    }
}