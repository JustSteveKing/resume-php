<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Certificate implements JsonSerializable
{
    use ValidatesDate;

    public DateTimeImmutable $date;

    /**
     * @param string $name
     * @param string|DateTimeImmutable $date
     * @param string $issuer
     * @param Url|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('date')]
        string|DateTimeImmutable $date,
        #[Field('issuer')]
        public string $issuer,
        #[Field('url')]
        public ?Url $url = null,
    ) {
        $this->date = is_string($date) ? new DateTimeImmutable($date) : $date;
    }

    /**
     * Convert the Certificate instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     date: string,
     *     issuer: string,
     *     url?: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'date' => $this->date->format('Y-m-d'),
            'issuer' => $this->issuer,
            'url' => $this->url?->jsonSerialize(),
        ];
    }
}
