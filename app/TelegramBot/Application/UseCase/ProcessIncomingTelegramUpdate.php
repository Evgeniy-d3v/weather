<?php

namespace App\TelegramBot\Application\UseCase;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\TelegramWebHookHandlerFactory;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;

final class ProcessIncomingTelegramUpdate
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
        public TelegramWebHookHandlerFactory $handlerCreator,
        public JobDispatcherInterface $dispatcher,
    ) {}

    public function handle(TelegramWebHookDto $dto): void
    {
        $handler = $this->handlerCreator->createHandler($dto);
        $messageDto = $handler->createResponse($dto);
        $this->dispatcher->dispatchSendMessage($messageDto);
    }
}
