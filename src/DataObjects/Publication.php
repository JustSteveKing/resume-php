<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Publication implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $name
     * @param string $publisher
     * @param string $releaseDate
     * @param Url|null $url
     * @param string|null $summary
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('publisher')]
        public string $publisher,
        #[Field('releaseDate')]
        public string $releaseDate,
        #[Field('url')]
        public ?Url $url = null,
        #[Field('summary')]
        public ?string $summary = null,
    ) {
        $this->assertDate($this->releaseDate);
    }

    /**
     * Convert the Publication instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     publisher: string,
     *     releaseDate: string,
     *     url?: string|null,
     *     summary?: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'publisher' => $this->publisher,
            'releaseDate' => $this->releaseDate,
            'url' => $this->url?->jsonSerialize(),
            'summary' => $this->summary,
        ];
    }
}
