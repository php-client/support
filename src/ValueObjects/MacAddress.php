<?php

declare(strict_types=1);

namespace PhpClient\Support\ValueObjects;

use PhpClient\Support\Exceptions\InvalidMacAddressException;

use const FILTER_VALIDATE_MAC;

final readonly class MacAddress
{
    private array $chunks;

    public function __construct(string $value)
    {
        $this->validate(value: $value);

        $this->chunks = mb_str_split(
            string: str_replace(
                search: ['-', ':', '.'],
                replace: '',
                subject: $value,
            ),
            length: 2,
        );
    }

    public function validate(string $value): void
    {
        $filtered = filter_var(
            value: $value,
            filter: FILTER_VALIDATE_MAC,
        );

        if ($filtered === false) {
            throw new InvalidMacAddressException(message: "Invalid MAC address");
        }
    }

    public function toHexString(): string
    {
        return implode(array: $this->chunks);
    }

    public function toDashSeparatedHexString(): string
    {
        return implode(separator: '-', array: $this->chunks);
    }

    public function toColonSeparatedHexString(): string
    {
        return implode(separator: ':', array: $this->chunks);
    }

    public function __toString(): string
    {
        return $this->toHexString();
    }
}
