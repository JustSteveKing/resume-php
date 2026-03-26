<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Project implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $name
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $description
     * @param list<string> $highlights
     * @param Url|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('startDate')]
        public ?string $startDate = null,
        #[Field('endDate')]
        public ?string $endDate = null,
        #[Field('description')]
        public ?string $description = null,
        #[Field('highlights')]
        public array $highlights = [],
        #[Field('url')]
        public ?Url $url = null,
    ) {
        if (null !== $this->startDate) {
            $this->assertDate($this->startDate);
        }

        if (null !== $this->endDate) {
            $this->assertDate($this->endDate);
        }
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
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'description' => $this->description,
            'highlights' => $this->highlights,
            'url' => $this->url?->jsonSerialize(),
        ];
    }
}
