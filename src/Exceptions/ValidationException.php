<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exceptions;

use RuntimeException;
use Throwable;

final class ValidationException extends RuntimeException
{
    /**
     * @param array<string, array<string>> $errors
     */
    public function __construct(
        string $message,
        private readonly array $errors = [],
        null|Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
