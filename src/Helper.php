<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerNotificationsV2;

use Generator;

final class Helper
{
    public static function arrayTypeCastGenerator(array $input, array $typeCastMap): Generator
    {
        foreach ($typeCastMap as $type => $keys) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $input)) {
                    continue;
                }

                yield $key => match ($type) {
                    'int' => (int) $input[$key],
                    'bool' => (bool) $input[$key],
                    'float' => (float) $input[$key],
                    'string' => (string) $input[$key],

                    default => null,
                };
            }
        }
    }
}
