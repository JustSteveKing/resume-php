<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Project implements JsonSerializable
{
    public ?DateTimeImmutable $startDate;
    public ?DateTimeImmutable $endDate;

    /**
     * @param string $name
<<<<<<< HEAD
     * @param string|DateTimeImmutable|null $startDate
     * @param string|DateTimeImmutable|null $endDate
=======
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
>>>>>>> feature/typed-dates
     * @param string|null $description
     * @param list<string> $highlights
     * @param Url|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('startDate')]
<<<<<<< HEAD
        string|DateTimeImmutable|null $startDate = null,
        #[Field('endDate')]
        string|DateTimeImmutable|null $endDate = null,
=======
        public ?\DateTimeImmutable $startDate = null,
        #[Field('endDate')]
        public ?\DateTimeImmutable $endDate = null,
>>>>>>> feature/typed-dates
        #[Field('description')]
        public ?string $description = null,
        #[Field('highlights')]
        public array $highlights = [],
        #[Field('url')]
        public ?Url $url = null,
    ) {
<<<<<<< HEAD
        $this->startDate = is_string($startDate) ? new DateTimeImmutable($startDate) : $startDate;
        $this->endDate = is_string($endDate) ? new DateTimeImmutable($endDate) : $endDate;
=======
        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
>>>>>>> feature/typed-dates
    }

    /**
     * Convert the Project instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     startDate?: string|null,
     *     endDate?: string|null,
     *     description?: string|null,
     *     highlights: list<string>,
     *     url?: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'description' => $this->description,
            'highlights' => $this->highlights,
            'url' => $this->url?->jsonSerialize(),
        ];
    }
}
