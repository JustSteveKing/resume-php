<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Enums\SkillLevel;

final readonly class Skill implements JsonSerializable
{
    /**
     * Create a new Skill instance.
     *
     * @param string $name The name of the skill.
     * @param SkillLevel|null $level The level of proficiency in the skill.
     * @param list<string> $keywords An array of keywords associated with the skill.
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('level')]
        public ?SkillLevel $level = null,
        #[Field('keywords')]
        public array $keywords = [],
    ) {}

    /**
     * Convert the Skill instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     level?: string,
     *     keywords?: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if (null !== $this->level) {
            $data['level'] = $this->level->value;
        }
        if ( ! empty($this->keywords)) {
            $data['keywords'] = $this->keywords;
        }

        return $data;
    }
}
