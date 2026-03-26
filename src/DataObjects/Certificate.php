<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Certificate implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $name
     * @param string $date
     * @param string $issuer
     * @param Url|null $url
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('date')]
        public string $date,
        #[Field('issuer')]
        public string $issuer,
        #[Field('url')]
        public ?Url $url = null,
    ) {
        $this->assertDate($this->date);
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
            'date' => $this->date,
            'issuer' => $this->issuer,
            'url' => $this->url?->jsonSerialize(),
        ];
    }
}
