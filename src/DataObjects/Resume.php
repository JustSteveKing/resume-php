<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Enums\ResumeSchema;
use JustSteveKing\Resume\Exceptions\ValidationException;
use JustSteveKing\Resume\Services\CareerAnalyzer;
use JustSteveKing\Resume\Services\Validator;

final readonly class Resume implements JsonSerializable
{
    /**
     * @param Basics $basics
     * @param list<Work> $work
     * @param list<Volunteer> $volunteer
     * @param list<Education> $education
     * @param list<Award> $awards
     * @param list<Certificate> $certificates
     * @param list<Publication> $publications
     * @param list<Skill> $skills
     * @param list<Language> $languages
     * @param list<Interest> $interests
     * @param list<Reference> $references
     * @param list<Project> $projects
     * @param ResumeSchema $schema
     */
    public function __construct(
        #[Field('basics')]
        public Basics $basics,
        #[Field('work')]
        public array $work = [],
        #[Field('volunteer')]
        public array $volunteer = [],
        #[Field('education')]
        public array $education = [],
        #[Field('awards')]
        public array $awards = [],
        #[Field('certificates')]
        public array $certificates = [],
        #[Field('publications')]
        public array $publications = [],
        #[Field('skills')]
        public array $skills = [],
        #[Field('languages')]
        public array $languages = [],
        #[Field('interests')]
        public array $interests = [],
        #[Field('references')]
        public array $references = [],
        #[Field('projects')]
        public array $projects = [],
        #[Field('$schema')]
        public ResumeSchema $schema = ResumeSchema::V1,
    ) {}

    /**
     * Get career insights for the résumé.
     *
     * @return CareerAnalyzer
     */
    public function getInsights(): CareerAnalyzer
    {
        return new CareerAnalyzer($this);
    }

    /**
     * @return non-empty-array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [
            '$schema' => $this->schema->value,
            'basics' => $this->basics->jsonSerialize(),
        ];

        // Only include non-empty arrays
        $arrayFields = [
            'work' => $this->work,
            'volunteer' => $this->volunteer,
            'education' => $this->education,
            'awards' => $this->awards,
            'certificates' => $this->certificates,
            'publications' => $this->publications,
            'skills' => $this->skills,
            'languages' => $this->languages,
            'interests' => $this->interests,
            'references' => $this->references,
            'projects' => $this->projects,
        ];

        foreach ($arrayFields as $key => $items) {
            if ( ! empty($items)) {
                $data[$key] = array_map(
                    static fn($item): array => $item->jsonSerialize(),
                    $items,
                );
            }
        }

        return $data;
    }

    /**
     * Get a summary of the resume content.
     *
     * @return array<string, int|bool|string|null>
     */
    public function getSummary(): array
    {
        return [
            'name' => $this->basics->name,
            'email' => $this->basics->email?->value,
            'work_experiences' => count($this->work),
            'education_entries' => count($this->education),
            'skills' => count($this->skills),
            'projects' => count($this->projects),
            'languages' => count($this->languages),
            'has_volunteer_experience' => ! empty($this->volunteer),
            'has_awards' => ! empty($this->awards),
            'has_publications' => ! empty($this->publications),
        ];
    }

    /**
     * Validate the résumé against the official JSON schema.
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        return (new Validator())->validate($this);
    }
}
