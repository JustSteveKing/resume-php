<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;

final readonly class Certificate implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $name
     * @param \DateTimeImmutable $date
     * @param string $issuer
     * @param string|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('date')]
        public \DateTimeImmutable $date,
        #[Field('issuer')]
        public string $issuer,
        #[Field('url')]
        public ?string $url = null,
    ) {}

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
            'url' => $this->url,
        ];
    }
}
