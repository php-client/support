<?php

declare(strict_types=1);

namespace PhpClient\Support\ValueObjects;

use PhpClient\Support\Exceptions\InvalidIpAddressException;

use const FILTER_FLAG_IPV6;
use const FILTER_VALIDATE_IP;

final readonly class IpAddressV6
{
    public function __construct(public string $value)
    {
        $this->validate(value: $value);
    }

    public function validate(string $value): void
    {
        if (filter_var(value: $value, filter: FILTER_VALIDATE_IP, options: FILTER_FLAG_IPV6) === false) {
            throw new InvalidIpAddressException(message: 'Invalid IPv6 address');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
