<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;

final readonly class Language implements JsonSerializable
{
    /**
     * Create a new Language instance.
     *
     * @param string $language The language name (e.g., "English").
     * @param string|null $fluency The fluency level (e.g., "Native", "Fluent", "Basic").
     */
    public function __construct(
        #[Field('language')]
        public string $language,
        #[Field('fluency')]
        public ?string $fluency = null,
    ) {}

    /**
     * Convert the Language instance to an array for JSON serialization.
     *
     * @return array{
     *     language: string,
     *     fluency?: string
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'language' => $this->language,
        ];

        if (null !== $this->fluency) {
            $data['fluency'] = $this->fluency;
        }

        return $data;
    }
}
