<?php

namespace App\TelegramBot\Infrastructure\Providers;

use App\TelegramBot\Application\TelegramBotApiInterface;
use App\TelegramBot\Infrastructure\Adapters\TelegramBotApiAdapter;
use App\TelegramBot\Presentation\Commands\AddWebHookCommand;
use App\TelegramBot\Presentation\Commands\DeleteWebHookCommand;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Api;

class TelegramBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Api::class, function () {
            return new Api(config('telegram.bot_token'));
        });

        $this->app->bind(TelegramBotApiInterface::class, TelegramBotApiAdapter::class);

    }


    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddWebHookCommand::class,
                DeleteWebHookCommand::class
            ]);
        }
    }
}
