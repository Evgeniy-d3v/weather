<?php

namespace App\TelegramBot\Application\UseCase;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Jobs\GetCityCoordinateJob;
use App\TelegramBot\Application\Jobs\SendCurrentWeatherJob;
use App\TelegramBot\Application\Jobs\SendTelegramBotMessageJob;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
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
        $client = $this->clientRepository->findByChatId($dto->chatId);

        if ($client === null) {
            $this->clientRepository->createNewClient($dto);
            $this->sendMessageToClient(
                $dto->chatId,
                MessageTextEnum::FIRST_MESSAGE->value,
                InlineKeyboard::subscriptionMenu());
            return;
        }

        if (!$client->is_subscribed) {
            switch ($dto->text) {
                case 'subscribe':
                    $client->is_subscribed = true;
                    $client->save();
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
           if ($client->city === null) {
               GetCityCoordinateJob::dispatch($dto->text, $client->id, $dto->chatId);
               $this->sendMessageToClient(
                   $dto->chatId,
                   MessageTextEnum::FIND_COORDINATE_MESSAGE->value
               );
           } else {
               //"isQuery":true
               if ($dto->text === 'change_city') {
                   $client->city = null;
                   $client->save();
                   $this->sendMessageToClient(
                       $dto->chatId,
                       MessageTextEnum::CHANGE_CITY_MESSAGE->value
                   );
                   return;
               }
               if ($dto->text === 'unsubscribe') {
                   $client->is_subscribed = false;
                   $client->save();
                   $this->sendMessageToClient(
                       $dto->chatId,
                       MessageTextEnum::UNSUBSCRIBE_MESSAGE->value
                   );
                   return;
               }
               if ($dto->text === 'get_current_weather') {
                   $this->sendMessageToClient(
                       $dto->chatId,
                      'current weather sending is under development'
                   );
//                   SendCurrentWeatherJob::dispatch($client->id, $dto->chatId);
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
