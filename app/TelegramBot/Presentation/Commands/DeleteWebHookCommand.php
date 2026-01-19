<?php

namespace App\TelegramBot\Presentation\Commands;

use App\TelegramBot\Application\TelegramBotApiInterface;
use Illuminate\Console\Command;

class DeleteWebHookCommand extends Command
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
    protected $signature = 'telegram:delete-webhook ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удалить вебхук для Telegram бота';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {


        try {
            $result = $this->telegramBot->deleteWebHook();
            if ($result) {
                $this->info("Вебхук успешно удален");
                return self::SUCCESS;
            } else {
                $this->error('Не удалось удалить вебхук');
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Ошибка при удаленнии вебхука: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
