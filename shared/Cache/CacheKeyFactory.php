<?php

namespace Cache;

class CacheKeyFactory
{
    //todo документировать методом
    public function generateUniqKey(string $cachePrefix, string|int ...$parts): string
    {
        return $cachePrefix . '=' . hash('sha256',(implode(':', $parts)));
    }
}
