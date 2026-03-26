<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Volunteer implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $organization
     * @param string $position
     * @param Url|null $url
     * @param string|null $startDate
     * @param string|null $endDate
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
        public ?string $startDate = null,
        #[Field('endDate')]
        public ?string $endDate = null,
        #[Field('summary')]
        public ?string $summary = null,
        #[Field('highlights')]
        public array $highlights = [],
    ) {
        if (null !== $this->startDate) {
            $this->assertDate($this->startDate);
        }

        if (null !== $this->endDate) {
            $this->assertDate($this->endDate);
        }
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
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'summary' => $this->summary,
            'highlights' => $this->highlights,
        ];
    }
}
