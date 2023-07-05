<?php

namespace Ads\Core\Support;

use Generator;
use Illuminate\Support\Facades\Redis as RedisSupport;

class Redis
{
    /**
     * Возвращает генератор с ключами, у которых тип равен $type.
     *
     * @param string $type
     * @return Generator
     */
    public static function scanByType(string $type): Generator
    {
        $cursor = 0;

        do {
            [$cursor, $keys] = RedisSupport::scan($cursor);

            foreach ($keys as $key) {
                if ((string)RedisSupport::type($key) !== $type) {
                    continue;
                }

                yield $key;
            }
        } while ($cursor);
    }
}
