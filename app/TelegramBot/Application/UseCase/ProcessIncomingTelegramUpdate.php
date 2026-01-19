<?php

namespace App\TelegramBot\Application\UseCase;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Jobs\SendTelegramBotMessageJob;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\MessageTextEnum;


final class ProcessIncomingTelegramUpdate
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
    )
    {}

    public function handle(TelegramWebHookDto $dto): void
    {
        $client = $this->clientRepository->findByChatId($dto->chatId);
        if ($client === null) {
            $this->clientRepository->createNewClient($dto);
            SendTelegramBotMessageJob::dispatch(new TelegramSendMessageDto(
                chatId: $dto->chatId,
                text: MessageTextEnum::FIRST_MESSAGE->value,
            ));
        }

    }
}
