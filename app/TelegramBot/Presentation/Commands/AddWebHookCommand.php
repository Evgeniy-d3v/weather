<?php

namespace App\TelegramBot\Presentation\Commands;

use App\TelegramBot\Application\TelegramBotApiInterface;
use Illuminate\Console\Command;

class AddWebHookCommand extends Command
{
    public function __construct(
        private readonly TelegramBotApiInterface $telegramBot
    ) {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Установить вебхук для Telegram бота';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $url = $this->argument('url') ?? config('telegram.webhook_url');

        if (!$url) {
            $this->error('URL вебхука не указан. Укажите его как аргумент или в конфиге telegram.webhook_url');
            return self::FAILURE;
        }

        try {
            $result = $this->telegramBot->setWebhook($url);

            if ($result) {
                $this->info("Вебхук успешно установлен: {$url}");
                return self::SUCCESS;
            } else {
                $this->error('Не удалось установить вебхук');
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Ошибка при установке вебхука: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
