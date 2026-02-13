<?php

namespace Tests\Unit\TelegramBot\Application\UseCase;

use App\TelegramBot\Application\DTO\TelegramSendMessageDto;
use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\TelegramWebHookHandlerFactory;
use App\TelegramBot\Application\Factory\TelegramWebHookHandlerInterface;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Application\UseCase\ProcessIncomingTelegramUpdate;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(ProcessIncomingTelegramUpdate::class) ]
class ProcessIncomingTelegramUpdateTest extends TestCase
{
    private mockObject|ClientRepositoryInterface $clientRepository;

    private mockObject|TelegramWebHookHandlerFactory $factory;

    private mockObject|JobDispatcherInterface $dispatcher;

    private mockObject|TelegramWebHookHandlerInterface $handler;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->factory = $this->createMock(TelegramWebHookHandlerFactory::class);
        $this->dispatcher = $this->createMock(JobDispatcherInterface::class);
        $this->handler = $this->createMock(TelegramWebHookHandlerInterface::class);
        $this->ProcessIncomingTelegramUpdate = new ProcessIncomingTelegramUpdate(
            $this->clientRepository,
            $this->factory,
            $this->dispatcher,
        );
        $this->telegramWebhookDto = new TelegramWebHookDto(
            false,
            1,
            'fakeUserFullName',
            'fakeUserName',
            'fakeText',
            null
        );
        $this->telegramSendMessageDto = new TelegramSendMessageDto(
            1,
            1,
            null
        );
    }

    public function test_method_handle_will_create_message_handler_create_response_and_call_dispatch_send_message_method(): void
    {
        $this->factory
            ->expects($this->once())
            ->method('createHandler')
            ->with($this->telegramWebhookDto)
            ->willReturn($this->handler);
        $this->handler
            ->expects($this->once())
            ->method('createResponse')
            ->with($this->telegramWebhookDto)
            ->willReturn($this->telegramSendMessageDto);
        $this->dispatcher
            ->expects($this->once())
            ->method('dispatchSendMessage');

        $this->ProcessIncomingTelegramUpdate->handle(
            $this->telegramWebhookDto
        );
    }
}
