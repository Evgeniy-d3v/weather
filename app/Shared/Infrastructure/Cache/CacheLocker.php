<?php

namespace App\Shared\Infrastructure\Cache;

use Illuminate\Support\Facades\Cache;

class CacheLocker
{
    public function __construct(
        private readonly CacheKeyFactory $keyGenerator
    ) {}

    /**
     * Возвращает true, если ключ удалось установить впервые (значит можно обрабатывать),
     * false — если уже был (значит дубль).
     */
    public function tryLock(string $prefix, int $ttl, string|int ...$parts): bool
    {
        $key = $this->keyGenerator->generateUniqKey($prefix, ...$parts);

        return Cache::add($key, true, $ttl);
    }
}
