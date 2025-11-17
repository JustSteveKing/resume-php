<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\Concerns\ValidatesUrl;

final readonly class Work implements JsonSerializable
{
    use ValidatesDate;
    use ValidatesUrl;

    /**
     * Create a new Work instance.
     *
     * @param string $name The name of the company or organization.
     * @param string $position The position held at the company.
     * @param string|null $location The location of the company or organization.
     * @param string|null $url The URL of the company or organization.
     * @param string|null $startDate The start date of employment in YYYY-MM-DD format.
     * @param string|null $endDate The end date of employment in YYYY-MM-DD format.
     * @param string|null $summary A brief summary of the work done.
     * @param list<string> $highlights An array of highlights or achievements during the employment.
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('position')]
        public string $position,
        #[Field('location')]
        public ?string $location = null,
        #[Field('url')]
        public ?string $url = null,
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

        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
    }

    /**
     * Convert the Work instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     position: string,
     *     location: ?string,
     *     url: ?string,
     *     startDate: ?string,
     *     endDate: ?string,
     *     summary: ?string,
     *     highlights: list<string>
     * } The array representation of the Work instance.
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'position' => $this->position,
            'url' => $this->url,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'summary' => $this->summary,
            'highlights' => $this->highlights,
        ];
    }
}
