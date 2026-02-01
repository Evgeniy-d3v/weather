<?php

namespace App\TelegramBot\Application\UseCase;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use App\TelegramBot\Infrastructure\Jobs\GetCityCoordinateJob;
use App\TelegramBot\Infrastructure\Jobs\SendCurrentWeatherJob;
use App\TelegramBot\Infrastructure\Jobs\SendTelegramBotMessageJob;
use Illuminate\Support\Facades\Log;


final class ProcessIncomingTelegramUpdate
{
    public function __construct(
        public ClientRepositoryInterface $clientRepository,
    )
    {}

    public function handle(TelegramWebHookDto $dto): void
    {

        Log::debug('TelegramWebHookDto: ' . json_encode($dto));
        if ($dto->webAppData !== null) {
            $this->clientRepository->updateClientFromWebAppData($dto);
            $this->sendMessageToClient(
                $dto->chatId,
                MessageTextEnum::WEB_APP_DATA_RECEIVED_MESSAGE->value,
            );
            return;
        }

        /** @var ClientEntity $client */
        $client = $this->clientRepository->findByChatId($dto->chatId);

        if ($client === null) {
            $this->clientRepository->createNewClient($dto);
            $this->sendMessageToClient(
                $dto->chatId,
                MessageTextEnum::FIRST_MESSAGE->value,
                InlineKeyboard::subscriptionMenu());
            return;
        }

        if (!$client->isSubscribed()) {
            switch ($dto->text) {
                case 'subscribe':
                    $this->clientRepository->saveSubscribe($client->getId());
                    $this->sendMessageToClient(
                        $dto->chatId,
                        MessageTextEnum::SUBSCRIBE_MESSAGE->value
                    );
                    break;
                case 'unsubscribe':
                    $this->sendMessageToClient(
                        $dto->chatId,
                        MessageTextEnum::UNSUBSCRIBE_MESSAGE->value
                    );
                    break;
                default:
                    $this->sendMessageToClient(
                        $dto->chatId,
                        MessageTextEnum::REMIND_SUBSCRIPTION_MESSAGE->value,
                        InlineKeyboard::subscriptionMenu()
                    );
                    break;
            }
        } else {
           if (!$client->hasCity()) {
               GetCityCoordinateJob::dispatch($dto->text, $client->getId(), $dto->chatId);
               $this->sendMessageToClient(
                   $dto->chatId,
                   MessageTextEnum::FIND_COORDINATE_MESSAGE->value
               );
           } else {
               //"isQuery":true
               if ($dto->text === 'change_city') {
                   $this->clientRepository->deleteCity($client->getId());
                   $this->sendMessageToClient(
                       $dto->chatId,
                       MessageTextEnum::CHANGE_CITY_MESSAGE->value
                   );
                   return;
               }
               if ($dto->text === 'unsubscribe') {
                   $this->sendMessageToClient(
                       $dto->chatId,
                       MessageTextEnum::UNSUBSCRIBE_MESSAGE->value
                   );

                   $this->clientRepository->deleteClient($client->getId());
                   return;
               }
               if ($dto->text === 'get_current_weather') {
                   Log::debug('get_current_weather');
                   SendCurrentWeatherJob::dispatch($client->getId());
                   return;
               }
               if ($dto->text === 'change_days') {
                   $this->sendMessageToClient(
                       $dto->chatId,
                       MessageTextEnum::CONFIGURE_NEWS_LETTER_MESSAGE->value,
                       InlineKeyboard::subscribeWeatherNewsLetterConfig()
                   );
                   return;
               }
               $this->sendMessageToClient(
                   $dto->chatId,
                   MessageTextEnum::COMMON_MESSAGE_FROM_CLIENT->value,
                   InlineKeyboard::mainMenu()
               );
           }
        }
    }
    private function sendMessageToClient(int $chatId, string $text, ?string $replyMarkup = null): void
    {
        SendTelegramBotMessageJob::dispatch(new TelegramSendMessageDto(
            chatId: $chatId,
            text: $text,
            replyMarkup:$replyMarkup
        ));
    }
}
