<?php

namespace App\TelegramBot\Application;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Domain\Entities\ClientEntity;

interface JobDispatcherInterface
{
    public function dispatchGetCityCoordinateJob(TelegramWebHookDto $dto, ClientEntity $client): void;

    public function dispatchHandleTelegramWebHookJob(array $payload): void;

    public function dispatchSendCurrentWeatherJob(int $clientId): void;

    public function dispatchSendForecastWhenReadyJob(int $client): void;

    public function dispatchSendMessage(TelegramSendMessageDto $telegramSendMessageDto): void;
}
