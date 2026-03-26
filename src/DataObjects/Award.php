<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;

final readonly class Award implements JsonSerializable
{
    use ValidatesDate;

    public DateTimeImmutable $date;

    /**
     * @param string $title
     * @param string|DateTimeImmutable $date
     * @param string $awarder
     * @param string|null $summary
     */
    public function __construct(
        #[Field('title')]
        public string $title,
        #[Field('date')]
        string|DateTimeImmutable $date,
        #[Field('awarder')]
        public string $awarder,
        #[Field('summary')]
        public ?string $summary = null,
    ) {
        $this->date = is_string($date) ? new DateTimeImmutable($date) : $date;
    }

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
