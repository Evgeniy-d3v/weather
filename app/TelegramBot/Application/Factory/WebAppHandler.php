<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\MessageTextEnum;

class WebAppHandler implements TelegramWebHookHandlerInterface
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
    ) {}

    public function createResponse(TelegramWebHookDto $dto): TelegramSendMessageDto
    {
        $this->clientRepository->updateClientFromWebAppData($dto);

        return new TelegramSendMessageDto(
            chatId: $dto->chatId,
            text: MessageTextEnum::WEB_APP_DATA_RECEIVED_MESSAGE->value,
        );
    }
}
