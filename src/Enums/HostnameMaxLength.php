<?php

declare(strict_types=1);

namespace PhpClient\Support\Enums;

enum HostnameMaxLength: int
{
    case MAX_LENGTH_IN_WINDOWS = 15;
    case MAX_LENGTH_IN_LINUX = 64;
    case MAX_LENGTH_IN_ANDROID = 128;

    public static function universal(): self
    {
        $universalLength = array_reduce(
            array: self::cases(),
            callback: static fn($carry, $item): int => min($item->value, $carry),
            initial: 256 // Initial must be greater than any case value
        );

        return self::from(value: $universalLength);
    }
}
