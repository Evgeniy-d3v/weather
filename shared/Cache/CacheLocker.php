<?php

namespace Cache;
use Illuminate\Support\Facades\Cache;

class CacheLocker
{
    /**
     * Возвращает true, если ключ удалось установить впервые (значит можно обрабатывать),
     * false — если уже был (значит дубль).
     */

    public function tryLock(string|int $uniqKey, int $ttl): bool
    {
        return Cache::add($uniqKey, true, $ttl);

    }
}
