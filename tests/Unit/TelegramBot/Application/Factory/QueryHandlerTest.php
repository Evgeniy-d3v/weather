<?php

namespace Tests\Unit\TelegramBot\Application\Factory;

use App\TelegramBot\Application\DTO\TelegramWebHookDto;
use App\TelegramBot\Application\Factory\QueryHandler;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use App\TelegramBot\Domain\Entities\ClientEntity;
use App\TelegramBot\Domain\Entities\InlineKeyboard;
use App\TelegramBot\Domain\Entities\MessageTextEnum;
use App\TelegramBot\Domain\Entities\QueryCommandEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\TelegramBot\Application\DataProvider\TelegramMessageHandlerDataProvider;

#[CoversClass(QueryHandler::class) ]
class QueryHandlerTest extends TestCase
{
    private mockObject|ClientRepositoryInterface $clientRepository;

    private mockObject|JobDispatcherInterface $dispatcher;

    private mockObject|QueryHandler $queryHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->dispatcher = $this->createMock(JobDispatcherInterface::class);
        $this->queryHandler = new QueryHandler(
            $this->clientRepository,
            $this->dispatcher,
        );

    }

    public function test_create_response_if_client_null(): void
    {
        $dto = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            'fakeText',
            null
        );
        $this->clientRepository
            ->expects($this->once())
            ->method('createNewClient');

        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($dto->chatId)
            ->willReturn(null);
        $result = $this->queryHandler->createResponse($dto);

        $this->assertSame($dto->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::FIRST_MESSAGE->value, $result->text);
        $this->assertEquals(InlineKeyboard::subscriptionMenu(), $result->replyMarkup);
    }

    #[DataProviderExternal(TelegramMessageHandlerDataProvider::class, 'queryHandlerTestCreateResponseIfClientNotNullClientRepositoryCases')]
    public function test_create_response_if_client_not_null_client_repository_cases(
        $telegramWebhookDto,
        $clientEntity,
        $methodName,
        $expectedResult
    ): void {
        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($telegramWebhookDto->chatId)
            ->willReturn($clientEntity);
        $this->clientRepository
            ->expects($this->never())
            ->method('createNewClient');
        $this->clientRepository
            ->expects($this->once())
            ->method($methodName);
        $result = $this->queryHandler->createResponse($telegramWebhookDto);

        $this->assertSame($expectedResult->chatId, $result->chatId);
        $this->assertSame($expectedResult->text, $result->text);
        $this->assertEquals($expectedResult->replyMarkup, $result->replyMarkup);

    }

    public function test_create_response_if_client_not_null_client_dispatcher_cases(): void
    {
        $telegramWebHookDtoCurrenWeather = new TelegramWebHookDto(
            true,
            1,
            'fakeUserFullName',
            'fakeUserName',
            QueryCommandEnum::CURRENT_WEATHER->value,
            null
        );
        $clientEntity = new ClientEntity(
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
            ->expects($this->once())
            ->method('findByChatId')
            ->with($telegramWebHookDtoCurrenWeather->chatId)
            ->willReturn($clientEntity);
        $this->clientRepository
            ->expects($this->never())
            ->method('createNewClient');
        $this->dispatcher
            ->expects($this->once())
            ->method('dispatchSendCurrentWeatherJob');

        $result = $this->queryHandler->createResponse($telegramWebHookDtoCurrenWeather);

        $this->assertSame($telegramWebHookDtoCurrenWeather->chatId, $result->chatId);
        $this->assertSame(MessageTextEnum::GET_CURRENT_WEATHER_FORECAST->value, $result->text);
        $this->assertEquals(null, $result->replyMarkup);

    }

    #[DataProviderExternal(TelegramMessageHandlerDataProvider::class, 'queryHandlerTestCreateResponseIfClientNotNullClientDontCallRepoAndDispatcherMethods')]
    public function test_create_response_if_client_not_null_client_dont_call_repo_and_dispatcher_methods(
        $telegramWebhookDto,
        $clientEntity,
        $expectedResult
    ): void {
        $this->clientRepository
            ->expects($this->once())
            ->method('findByChatId')
            ->with($telegramWebhookDto->chatId)
            ->willReturn($clientEntity);

        $result = $this->queryHandler->createResponse($telegramWebhookDto);

        $this->assertSame($expectedResult->chatId, $result->chatId);
        $this->assertSame($expectedResult->text, $result->text);
        $this->assertEquals($expectedResult->replyMarkup, $result->replyMarkup);

    }
}
