<?php

namespace Tests\Unit\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\CommonMessageHandler;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(CommonMessageHandler::class) ]
class CommonMessageHandlerTest extends TestCase
{
    private mockObject|ClientRepositoryInterface $clientRepository;

    private mockObject|JobDispatcherInterface $dispatcher;

    private mockObject|CommonMessageHandler $commonMessageHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->dispatcher = $this->createMock(JobDispatcherInterface::class);
        $this->commonMessageHandler = new CommonMessageHandler(
            $this->clientRepository,
            $this->dispatcher,
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

    public function test_create_response_with_null_client(): void
    {
        $this->clientRepository
            ->expects($this->once())
            ->method('createNewClient');

        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($this->dto->chatId)
            ->willReturn(null);
        $result = $this->commonMessageHandler->createResponse($this->dto);

        $this->assertSame($this->dto->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::FIRST_MESSAGE->value, $result->text);
        $this->assertEquals(InlineKeyboard::subscriptionMenu(), $result->replyMarkup);
    }

    public function test_create_response_client_without_city(): void
    {
        $client = new ClientEntity(
            1,
            1,
            'fakeUserFullName',
            'fakeUserName',
            true,
            null,
            null,
            null,
            null,
        );
        $this->clientRepository
            ->expects($this->never())
            ->method('createNewClient');
        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($this->dto->chatId)
            ->willReturn($client);
        $this->dispatcher
            ->expects($this->once())
            ->method('dispatchGetCityCoordinateJob');

        $result = $this->commonMessageHandler->createResponse($this->dto);

        $this->assertSame($this->dto->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::FIND_COORDINATE_MESSAGE->value, $result->text);
        $this->assertEquals(null, $result->replyMarkup);
    }

    public function test_create_response_with_client_with_city(): void
    {
        $client = new ClientEntity(
            1,
            1,
            'fakeUserFullName',
            'fakeUserName',
            true,
            1,
            null,
            null,
            null,
        );
        $this->clientRepository
            ->expects($this->never())
            ->method('createNewClient');

        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($this->dto->chatId)
            ->willReturn($client);

        $result = $this->commonMessageHandler->createResponse($this->dto);

        $this->assertSame($this->dto->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::COMMON_MESSAGE_FROM_CLIENT->value, $result->text);
        $this->assertEquals(InlineKeyboard::mainMenu(), $result->replyMarkup);
    }
}
