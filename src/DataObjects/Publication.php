<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\Concerns\ValidatesUrl;

final readonly class Publication implements JsonSerializable
{
    use ValidatesDate;
    use ValidatesUrl;

    public DateTimeImmutable $releaseDate;

    /**
     * @param string $name
     * @param string $publisher
     * @param string|DateTimeImmutable $releaseDate
     * @param string|null $url
     * @param string|null $summary
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('publisher')]
        public string $publisher,
        #[Field('releaseDate')]
        string|DateTimeImmutable $releaseDate,
        #[Field('url')]
        public ?string $url = null,
        #[Field('summary')]
        public ?string $summary = null,
    ) {
        $this->releaseDate = is_string($releaseDate) ? new DateTimeImmutable($releaseDate) : $releaseDate;

        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
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
            'releaseDate' => $this->releaseDate->format('Y-m-d'),
            'url' => $this->url,
            'summary' => $this->summary,
        ];
    }
}
