<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

final readonly class Url implements JsonSerializable, Stringable
{
    /**
     * @param string $value
     */
    public function __construct(
        public string $value,
    ) {
        if ('' === mb_trim($value)) {
            throw new InvalidArgumentException('URL cannot be empty');
        }

        if ( ! filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Invalid URL format: {$value}");
        }

        $parsed = parse_url($value);
        if ( ! is_array($parsed) || ! isset($parsed['scheme']) || ! in_array($parsed['scheme'], ['http', 'https'])) {
            throw new InvalidArgumentException("URL must have a valid scheme (http, https): {$value}");
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
