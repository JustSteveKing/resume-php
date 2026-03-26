<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Volunteer implements JsonSerializable
{
    public ?DateTimeImmutable $startDate;
    public ?DateTimeImmutable $endDate;

    /**
     * @param string $organization
     * @param string $position
     * @param Url|null $url
     * @param string|DateTimeImmutable|null $startDate
     * @param string|DateTimeImmutable|null $endDate
     * @param string|null $summary
     * @param list<string> $highlights
     */
    public function __construct(
        #[Field('organization')]
        public string $organization,
        #[Field('position')]
        public string $position,
        #[Field('url')]
        public ?Url $url = null,
        #[Field('startDate')]
        string|DateTimeImmutable|null $startDate = null,
        #[Field('endDate')]
        string|DateTimeImmutable|null $endDate = null,
        #[Field('summary')]
        public ?string $summary = null,
        #[Field('highlights')]
        public array $highlights = [],
    ) {
        $this->startDate = is_string($startDate) ? new DateTimeImmutable($startDate) : $startDate;
        $this->endDate = is_string($endDate) ? new DateTimeImmutable($endDate) : $endDate;
    }

    /**
     * Convert the Volunteer instance to an array for JSON serialization.
     *
     * @return array{
     *     organization: string,
     *     position: string,
     *     url: ?string,
     *     startDate: ?string,
     *     endDate: ?string,
     *     summary: ?string,
     *     highlights: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'organization' => $this->organization,
            'position' => $this->position,
            'url' => $this->url?->jsonSerialize(),
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'summary' => $this->summary,
            'highlights' => $this->highlights,
        ];
    }
}
