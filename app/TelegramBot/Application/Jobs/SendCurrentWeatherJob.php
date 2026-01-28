<?php

namespace App\TelegramBot\Application\Jobs;

use App\TelegramBot\Infrastructure\Persistence\Model\Client;
use Shared\Job\AbstractJob;

class SendCurrentWeatherJob extends AbstractJob
{
    //$client->id, $dto->chatId
    public function __construct(
        public int $clientId,
        public int $chatId,
    )
    {
        $this->onQueue('send_current_weather');

    }

    public function handle(

    ): void
    {
        $client = Client::query()->first($this->clientId);
        $weatherForecasts = $client->city->weatherForecasts;

    }
}
