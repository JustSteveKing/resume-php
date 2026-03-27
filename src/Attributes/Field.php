<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final readonly class Field
{
    public function __construct(
        public string $name,
    ) {}
}
