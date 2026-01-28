<?php

namespace App\TelegramBot\Domain\Entities;

enum MessageTextEnum: string
{
    case FIRST_MESSAGE = 'Привет! Я высылать тебе данные по погоде в твоем населенном пункте, что бы бы продолжить вебри "подписаться"';
    case UNSUBSCRIBE_MESSAGE = 'Ну не очень то мне и хотелось тебе что то слать... Пока!';
    case GET_CITY_INFO_EXCEPTION = 'Че то не получилось найти, уверн(а) что вбил название как в гугле?';
    case REMIND_SUBSCRIPTION_MESSAGE = 'Ты знаешь что нужно сделать...';
    case FIND_COORDINATE_MESSAGE = 'Отлично! Я ищу твой город, покури 2 минуты.';
    case SUBSCRIBE_MESSAGE = 'Ля какой ты герой! Напиши мне название своего населоного пунка (если не знаешь как пишется, то загугли).';
    case CITY_FOUND = 'Ура! Я нашел твой город! Заполни форму для рассылки погоды';
    case COMMON_MESSAGE_FROM_CLIENT = 'Вижы ты что то хочешь попросить, вот что я могу: ';
    case CHANGE_CITY_MESSAGE = 'Хорошо, напиши мне название нового населенного пункта.';
    case CONFIGURE_NEWS_LETTER_MESSAGE = 'Отлично! Теперь поменяй дни и время когда ты хочешь получать погоду.';
    case WEB_APP_DATA_RECEIVED_MESSAGE = 'Отлично! Я получил твои настройки. Если что-то пойдет не так, ты всегда можешь изменить их в главном меню. Ps что бы получить меню отправь любое сообщение.';
}
