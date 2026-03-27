<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

final readonly class Email implements JsonSerializable, Stringable
{
    /**
     * @param string $value
     */
    public function __construct(
        public string $value,
    ) {
        if ('' === mb_trim($value)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        // Additional validation for common issues
        if (mb_strlen($value) > 254) {
            throw new InvalidArgumentException('Email address is too long (max 254 characters)');
        }

        // Check for valid domain
        $parts = explode('@', $value);
        if (2 !== count($parts)) {
            throw new InvalidArgumentException("Invalid email format: {$value}");
        }

        [, $domain] = $parts;
        if ( ! $this->isValidDomain($domain)) {
            throw new InvalidArgumentException("Invalid email domain: {$domain}");
        }

        // Final check with filter_var
        if ( ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    private function isValidDomain(string $domain): bool
    {
        // Basic domain validation
        if (mb_strlen($domain) > 253) {
            return false;
        }

        // Check if domain has at least one dot
        if ( ! str_contains($domain, '.')) {
            return false;
        }

        // Check for valid characters
        return 1 === preg_match('/^[a-zA-Z0-9.-]+$/', $domain);
    }
}
