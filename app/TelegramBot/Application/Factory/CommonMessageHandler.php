<?php

namespace App\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;

class CommonMessageHandler implements TelegramWebHookHandlerInterface
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
        public JobDispatcherInterface $dispatcher
    ) {}

    public function createResponse(TelegramWebHookDto $dto): TelegramSendMessageDto
    {

        $client = $this->clientRepository->findByChatId($dto->chatId);
        if ($client === null) {
            $this->clientRepository->createNewClient($dto);

            return new TelegramSendMessageDto(
                $dto->chatId,
                MessageTextEnum::FIRST_MESSAGE->value,
                InlineKeyboard::subscriptionMenu()
            );
        }
        if (! $client->getCityId()) {
            $this->dispatcher->dispatchGetCityCoordinateJob($dto, $client);

            return new TelegramSendMessageDto(
                $dto->chatId,
                MessageTextEnum::FIND_COORDINATE_MESSAGE->value
            );
        }

        return new TelegramSendMessageDto(
            $dto->chatId,
            MessageTextEnum::COMMON_MESSAGE_FROM_CLIENT->value,
            InlineKeyboard::mainMenu()
        );
    }
}
