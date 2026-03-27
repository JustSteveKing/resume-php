<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exceptions;

use RuntimeException;
use Throwable;

final class HydrationException extends RuntimeException
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
