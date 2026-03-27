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
     *     url?: string,
     *     startDate?: string,
     *     endDate?: string,
     *     summary?: string,
     *     highlights?: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'organization' => $this->organization,
            'position' => $this->position,
        ];

        if (null !== $this->url) {
            $data['url'] = $this->url->jsonSerialize();
        }
        if (null !== $this->startDate) {
            $data['startDate'] = $this->startDate->format('Y-m-d');
        }
        if (null !== $this->endDate) {
            $data['endDate'] = $this->endDate->format('Y-m-d');
        }
        if (null !== $this->summary) {
            $data['summary'] = $this->summary;
        }
        if ( ! empty($this->highlights)) {
            $data['highlights'] = $this->highlights;
        }

        return $data;
    }
}
