<?php

namespace Tests\Unit\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\WebAppHandler;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(WebAppHandler::class) ]
class WebAppHandlerTest extends TestCase
{
    private mockObject|ClientRepositoryInterface $clientRepository;

    private mockObject|WebAppHandler $webAppHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->webAppHandler = new WebAppHandler(
            $this->clientRepository,
        );
        $this->dto = new TelegramWebHookDto(
            false,
            1,
            'fakeUserFullName',
            'fakeUserName',
            'fakeText',
            null
        );
    }

    public function test_create_response(): void
    {
        $this->clientRepository
            ->expects($this->once())
            ->method('updateClientFromWebAppData')
            ->with($this->dto);

        $result = $this->webAppHandler->createResponse($this->dto);

        $this->assertSame($this->dto->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::WEB_APP_DATA_RECEIVED_MESSAGE->value, $result->text);
        $this->assertEquals(null, $result->replyMarkup);
    }
}
