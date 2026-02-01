<?php

namespace App\TelegramBot\Domain\Entities;

final class InlineKeyboard
{
    public static function subscriptionMenu(): string
    {
        return json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'Подписаться', 'callback_data' => 'subscribe'],
                    ['text' => 'Не подписываться', 'callback_data' => 'unsubscribe'],
                ],
            ],
        ]);
    }

    public static function mainMenu(): string
    {
        return json_encode([
            'inline_keyboard' => [
                [
                    ['text' => 'Получить текущую погоду', 'callback_data' => 'get_current_weather'],
                ],
                [
                    ['text' => 'Изменить город', 'callback_data' => 'change_city'],
                ],
                [
                    ['text' => 'Изменить настройки рассылки', 'callback_data' => 'change_days'],
                ],
                [
                    ['text' => 'Отписаться', 'callback_data' => 'unsubscribe'],
                ],
            ],
        ]);
    }

    public static function subscribeWeatherNewsLetterConfig(): string
    {
        return json_encode([
            'keyboard' => [[
                [
                    'text' => '📅 Настроить расписание',
                    'web_app' => ['url' => 'https://hello-domen.ru/api/telegram/webapp/weather-newsletter-config'],
                ],
            ]],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);
    }
}
