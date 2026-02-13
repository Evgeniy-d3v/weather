<?php

namespace Tests\Unit\TelegramBot\Application\Factory;

use App\TelegramBot\Application\Factory\TelegramWebHookHandlerFactory;
use App\TelegramBot\Application\JobDispatcherInterface;
use App\TelegramBot\Application\Repositories\ClientRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\TelegramBot\Application\DataProvider\TelegramWebHookFactoryDataProvider;

#[CoversClass(TelegramWebHookHandlerFactory::class) ]
class TelegramWebHookHandlerFactoryTest extends TestCase
{
    private mockObject|ClientRepositoryInterface $clientRepository;

    private mockObject|JobDispatcherInterface $dispatcher;

    private mockObject|TelegramWebHookHandlerFactory $telegramWebHookHandlerFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientRepository = $this->createMock(ClientRepositoryInterface::class);
        $this->dispatcher = $this->createMock(JobDispatcherInterface::class);
        $this->telegramWebHookHandlerFactory = new TelegramWebHookHandlerFactory(
            $this->clientRepository,
            $this->dispatcher,
        );
    }

    #[DataProviderExternal(TelegramWebHookFactoryDataProvider::class, 'createHandlerDataProvider')]
    public function test_method_create_handle_will_create_expected_handler($dto, $expectedClass): void
    {
        $result = $this->telegramWebHookHandlerFactory->createHandler($dto);

        $this->assertInstanceOf($expectedClass, $result);

    }
}
