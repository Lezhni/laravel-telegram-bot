<?php

namespace App\Services\Telegram\AlgorithmSteps;

use App\Services\Telegram\AwareBotApi;
use App\Services\Telegram\AwareMessage;
use App\Services\Telegram\Commands\Start;
use App\Services\Telegram\Storages\AlgorithmStepStorage;
use App\Services\Telegram\Storages\NavigationStorage;
use App\Services\TestimonialsService;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * Class StoreUserTestimonial
 * @package App\Services\Telegram\AlgorithmSteps
 */
class StoreUserTestimonial implements Step
{
    use AwareBotApi, AwareMessage;

    /**
     * @var \App\Services\TestimonialsService
     */
    private TestimonialsService $testimonialService;

    /**
     * StoreUserTestimonial constructor.
     * @param \App\Services\TestimonialsService $testimonialsService
     */
    public function __construct(TestimonialsService $testimonialsService)
    {
        $this->testimonialService = $testimonialsService;
    }

    /**
     * @return void
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function handle(): void
    {
        $chatId = $this->message->getChat()->getId();

        $testimonialText = $this->message->getText();
        $testimonialText = trim($testimonialText);
        if ($testimonialText == null || empty($testimonialText)) {
            $this->botApi->sendMessage($chatId, 'Пожалуйста, введите текст отзыва, или нажмите "Назад", чтобы вернуться в главное меню:');
            return;
        }

        try {
            $userName = $this->message->getChat()->getUsername();
            $this->testimonialService->createTestimonial($userName, $testimonialText);
        } catch (Throwable $e) {
            $this->botApi->sendMessage($chatId, 'Произошла непредвиденная ошибка, введите текст отзыва еще раз:');
            return;
        }

        AlgorithmStepStorage::reset($chatId);
        NavigationStorage::reset($chatId);

        $this->botApi->sendMessage($chatId, 'Спасибо за ваш отзыв!');

        $nextStep = App::make(Start::class);
        $nextStep->setBotApi($this->botApi);
        $nextStep->setMessage($this->message);
        $nextStep->handle();
    }
}