<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;

final readonly class Interest implements JsonSerializable
{
    /**
     * Create a new Interest instance.
     *
     * @param string $name The name of the interest.
     * @param list<string> $keywords A list of keywords associated with the interest.
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('keywords')]
        public array $keywords = [],
    ) {}

    /**
     * Convert the Interest instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     keywords?: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if ( ! empty($this->keywords)) {
            $data['keywords'] = $this->keywords;
        }

        return $data;
    }
}
