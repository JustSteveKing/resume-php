<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\Concerns\ValidatesUrl;

final readonly class Project implements JsonSerializable
{
    use ValidatesDate;
    use ValidatesUrl;

    /**
     * @param string $name
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
     * @param string|null $description
     * @param list<string> $highlights
     * @param string|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('startDate')]
        public ?\DateTimeImmutable $startDate = null,
        #[Field('endDate')]
        public ?\DateTimeImmutable $endDate = null,
        #[Field('description')]
        public ?string $description = null,
        #[Field('highlights')]
        public array $highlights = [],
        #[Field('url')]
        public ?string $url = null,
    ) {
        if (null !== $this->url) {
            $this->assertUrl($this->url);
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
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'description' => $this->description,
            'highlights' => $this->highlights,
            'url' => $this->url,
        ];
    }
}
