<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;

final class JobDescription implements JsonSerializable
{
    /**
     * Create a new JobDescription instance.
     *
     * @param string $name The name of the job description.
     * @param string|null $location The location of the job.
     * @param string|null $description A brief description of the job.
     * @param list<string> $highlights Key highlights of the job.
     * @param list<string> $skills Required skills for the job.
     * @param list<string> $tools Tools used in the job.
     * @param list<string> $responsibilities Responsibilities associated with the job.
     * @param list<string> $deliverables Expected deliverables from the job.
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('location')]
        public ?string $location = null,
        #[Field('description')]
        public ?string $description = null,
        #[Field('highlights')]
        public array $highlights = [],
        #[Field('skills')]
        public array $skills = [],
        #[Field('tools')]
        public array $tools = [],
        #[Field('responsibilities')]
        public array $responsibilities = [],
        #[Field('deliverables')]
        public array $deliverables = [],
    ) {}

    /**
     * Convert the JobDescription instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     location?: string|null,
     *     description?: string|null,
     *     highlights: list<string>,
     *     skills: list<string>,
     *     tools: list<string>,
     *     responsibilities: list<string>,
     *     deliverables: list<string>,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'highlights' => $this->highlights,
            'skills' => $this->skills,
            'tools' => $this->tools,
            'responsibilities' => $this->responsibilities,
            'deliverables' => $this->deliverables,
        ];
    }
}
