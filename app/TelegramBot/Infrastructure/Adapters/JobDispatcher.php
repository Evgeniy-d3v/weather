<?php

namespace App\TelegramBot\Infrastructure\Adapters;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Infrastructure\Jobs\GetCityCoordinateJob;
use App\TelegramBot\Infrastructure\Jobs\HandleTelegramWebHookJob;
use App\TelegramBot\Infrastructure\Jobs\SendCurrentWeatherJob;
use App\TelegramBot\Infrastructure\Jobs\SendForecastWhenReadyJob;
use App\TelegramBot\Infrastructure\Jobs\SendTelegramBotMessageJob;

class JobDispatcher implements JobDispatcherInterface
{
    public function dispatchGetCityCoordinateJob(TelegramWebHookDto $dto, ClientEntity $client): void
    {
        GetCityCoordinateJob::dispatch($dto->text, $client->getId(), $dto->chatId);
    }

    public function dispatchHandleTelegramWebHookJob(array $payload): void
    {
        HandleTelegramWebHookJob::dispatch($payload);
    }

    public function dispatchSendCurrentWeatherJob(int $clientId): void
    {
        SendCurrentWeatherJob::dispatch($clientId);
    }

    public function dispatchSendForecastWhenReadyJob(int $client): void
    {
        SendForecastWhenReadyJob::dispatch($client);
    }

    public function dispatchSendMessage(TelegramSendMessageDto $telegramSendMessageDto): void
    {
        SendTelegramBotMessageJob::dispatch($telegramSendMessageDto);
    }
}
