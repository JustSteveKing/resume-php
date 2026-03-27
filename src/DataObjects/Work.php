<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Work implements JsonSerializable
{
    public ?DateTimeImmutable $startDate;
    public ?DateTimeImmutable $endDate;

    /**
     * Create a new Work instance.
     *
     * @param string $name The name of the company or organization.
     * @param string $position The position held at the company.
     * @param string|null $location The location of the company or organization.
     * @param Url|null $url The URL of the company or organization.
     * @param string|DateTimeImmutable|null $startDate The start date of employment.
     * @param string|DateTimeImmutable|null $endDate The end date of employment.
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
     * Convert the Work instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     position: string,
     *     location?: string,
     *     url?: string,
     *     startDate?: string,
     *     endDate?: string,
     *     summary?: string,
     *     highlights?: list<string>
     * } The array representation of the Work instance.
     */
    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
            'position' => $this->position,
        ];

        if (null !== $this->location) {
            $data['location'] = $this->location;
        }
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
