<?php

namespace App\TelegramBot\Domain\Entities;

enum MessageTextEnum: string
{
    case GET_CURRENT_WEATHER_FORECAST = 'Подготавливаю отчёт…';

    case FIRST_MESSAGE =
    'Привет! Я буду присылать тебе данные о погоде в твоём населённом пункте. '
    .'Чтобы продолжить, выбери «Подписаться».';

    case UNSUBSCRIBE_MESSAGE =
    'Ну, не очень-то мне и хотелось тебе что-то слать… Пока!';

    case GET_CITY_INFO_EXCEPTION =
    'Что-то не получилось найти город. Ты уверен(а), что ввёл(а) название так же, как в Google?';

    case REMIND_SUBSCRIPTION_MESSAGE =
    'Ты знаешь, что нужно сделать…';

    case FIND_COORDINATE_MESSAGE =
    'Отлично! Я ищу твой город, подожди пару минут.';

    case SUBSCRIBE_MESSAGE =
    'Вот это да! Напиши мне название своего населённого пункта '
    .'(если не уверен(а) в написании — лучше загугли).';

    case CITY_FOUND =
    'Ура! Я нашёл твой город. Заполни форму для рассылки погоды.';

    case COMMON_MESSAGE_FROM_CLIENT =
    'Вижу, ты хочешь что-то спросить. Вот что я умею:';

    case EXCEPTION = 'Что - то пошло не так...';
    case CHANGE_CITY_MESSAGE =
    'Хорошо, напиши название нового населённого пункта.';

    case CONFIGURE_NEWS_LETTER_MESSAGE =
    'Отлично! Теперь выбери дни и время, когда ты хочешь получать погоду.';

    case WEB_APP_DATA_RECEIVED_MESSAGE =
    'Отлично! Я получил твои настройки. '
    .'Если что-то пойдёт не так, ты всегда сможешь изменить их в главном меню. '
    .'P.S. Чтобы открыть меню, отправь любое сообщение.';
}
