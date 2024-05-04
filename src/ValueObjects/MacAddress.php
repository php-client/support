<?php

declare(strict_types=1);

namespace PhpClient\Support\ValueObjects;

use PhpClient\Support\Exceptions\InvalidMacAddressException;

use const FILTER_VALIDATE_MAC;

final readonly class MacAddress
{
    public string $value;

    public function __construct(string $value)
    {
        $this->validate(value: $value);
        $this->value = $this->sanitize(value: $value);
    }

    private function sanitize(string $value): string
    {
        $hex = str_replace(search: ['-', ':', '.'], replace: '', subject: $value);
        $chunks = mb_str_split(string: $hex, length: 2);

        return implode(separator: ':', array: $chunks);
    }

    private function validate(string $value): void
    {
        if (filter_var(value: $value, filter: FILTER_VALIDATE_MAC) === false) {
            throw new InvalidMacAddressException(message: "Invalid MAC address");
        }
    }

    public function toColonSeparatedHexString(): string
    {
        return $this->value;
    }

    public function toHexString(): string
    {
        return str_replace(search: ':', replace: '', subject: $this->value);
    }

    public function toDashSeparatedHexString(): string
    {
        return str_replace(search: ':', replace: '-', subject: $this->value);
    }

    public function __toString(): string
    {
        return $this->toColonSeparatedHexString();
    }
}
