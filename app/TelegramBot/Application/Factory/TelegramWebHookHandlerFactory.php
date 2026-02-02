<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;

class TelegramWebHookHandlerFactory
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
        public JobDispatcherInterface $dispatcher,
    ) {}

    public function createHandler(TelegramWebHookDto $dto): TelegramWebHookHandlerInterface
    {
        return match (true) {
            $dto->webAppData !== null => new WebAppHandler($this->clientRepository),
            $dto->isQuery === true => new QueryHandler($this->clientRepository, $this->dispatcher),
            default => new CommonMessageHandler($this->clientRepository, $this->dispatcher)
        };
    }
}
