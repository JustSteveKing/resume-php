<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Publication implements JsonSerializable
{
    public DateTimeImmutable $releaseDate;

    /**
     * @param string $name
     * @param string $publisher
<<<<<<< HEAD
     * @param string|DateTimeImmutable $releaseDate
     * @param Url|null $url
=======
     * @param \DateTimeImmutable $releaseDate
     * @param string|null $url
>>>>>>> feature/typed-dates
     * @param string|null $summary
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('publisher')]
        public string $publisher,
        #[Field('releaseDate')]
<<<<<<< HEAD
        string|DateTimeImmutable $releaseDate,
=======
        public \DateTimeImmutable $releaseDate,
>>>>>>> feature/typed-dates
        #[Field('url')]
        public ?Url $url = null,
        #[Field('summary')]
        public ?string $summary = null,
    ) {
<<<<<<< HEAD
        $this->releaseDate = is_string($releaseDate) ? new DateTimeImmutable($releaseDate) : $releaseDate;
=======
        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
>>>>>>> feature/typed-dates
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
<<<<<<< HEAD
            'url' => $this->url?->jsonSerialize(),
=======
            'url' => $this->url,
>>>>>>> feature/typed-dates
            'summary' => $this->summary,
        ];
    }
}
