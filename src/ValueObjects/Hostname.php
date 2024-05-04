<?php

declare(strict_types=1);

namespace PhpClient\Support\ValueObjects;

use PhpClient\Support\Enums\HostnameMaxLength;
use PhpClient\Support\Exceptions\InvalidHostnameException;

use const FILTER_FLAG_HOSTNAME;
use const FILTER_VALIDATE_DOMAIN;

final class Hostname
{
    private static HostnameMaxLength $maxLength;

    public function __construct(public string $value)
    {
        if(self::$maxLength === null) {
            self::setMaxLength(maxLength: HostnameMaxLength::universal());
        }

        $this->validateLength(value: $value);
        $this->validateCharacters(value: $value);
    }

    public static function setMaxLength(HostnameMaxLength $maxLength): void
    {
        self::$maxLength = $maxLength;
    }

    private function validateLength(string $value): void
    {
        $length = mb_strlen(string: $value);

        if ($length === 0) {
            throw new InvalidHostnameException(message: 'Hostname is empty.');
        } elseif ($length > self::$maxLength->value) {
            throw new InvalidHostnameException(message: 'Hostname is too long.');
        }
    }

    public function validateCharacters(string $value): void
    {
        $filtered = filter_var(
            value: $value,
            filter: FILTER_VALIDATE_DOMAIN,
            options: FILTER_FLAG_HOSTNAME,
        );

        if ($filtered === false) {
            throw new InvalidHostnameException(message: 'Hostname has an unsupported characters.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
