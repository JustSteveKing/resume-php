<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;

final readonly class Award implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $title
     * @param \DateTimeImmutable $date
     * @param string $awarder
     * @param string|null $summary
     */
    public function __construct(
        #[Field('title')]
        public string $title,
        #[Field('date')]
        public \DateTimeImmutable $date,
        #[Field('awarder')]
        public string $awarder,
        #[Field('summary')]
        public ?string $summary = null,
    ) {}

    /**
     * Convert the Award instance to an array for JSON serialization.
     *
     * @return array{
     *     title: string,
     *     date: string,
     *     awarder: string,
     *     summary?: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'date' => $this->date->format('Y-m-d'),
            'awarder' => $this->awarder,
            'summary' => $this->summary,
        ];
    }
}
