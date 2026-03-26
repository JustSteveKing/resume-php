<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Enums\ResumeSchema;

final readonly class Resume implements JsonSerializable
{
    /**
     * @param ResumeSchema $schema
     * @param Basics $basics
     * @param array<Work> $work
     * @param array<Volunteer> $volunteer
     * @param array<Education> $education
     * @param array<Award> $awards
     * @param array<Certificate> $certificates
     * @param array<Publication> $publications
     * @param array<Skill> $skills
     * @param array<Language> $languages
     * @param array<Interest> $interests
     * @param array<Reference> $references
     * @param array<Project> $projects
     */
    public function __construct(
        #[Field('basics')]
        public Basics       $basics,
        #[Field('work')]
        public array        $work = [],
        #[Field('volunteer')]
        public array        $volunteer = [],
        #[Field('education')]
        public array        $education = [],
        #[Field('awards')]
        public array        $awards = [],
        #[Field('certificates')]
        public array        $certificates = [],
        #[Field('publications')]
        public array        $publications = [],
        #[Field('skills')]
        public array        $skills = [],
        #[Field('languages')]
        public array        $languages = [],
        #[Field('interests')]
        public array        $interests = [],
        #[Field('references')]
        public array        $references = [],
        #[Field('projects')]
        public array        $projects = [],
        #[Field('$schema')]
        public ResumeSchema $schema = ResumeSchema::V1,
    ) {}

    /**
     * @return non-empty-array<'$schema'|'basics'|'work'|'volunteer'|'education'|'awards'|'certificates'|'publications'|'skills'|'languages'|'interests'|'references'|'projects', mixed>
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
            'email' => $this->basics->email,
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
     * Transform the résumé into a structured array for JSON-LD.
     *
     * @return array<string, mixed>
     */
    public function toJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $this->basics->name,
            'url' => $this->basics->url,
            'jobTitle' => $this->basics->label,
            'sameAs' => array_filter(array_map(
                static fn($profile): ?string => $profile->url,
                $this->basics->profiles,
            )),
            'knowsAbout' => array_map(
                static fn($skill): string => $skill->name,
                $this->skills,
            ),
        ];
    }

    /**
     * Convert the résumé to a Markdown string.
     *
     * @param array{
     *     basics:bool,
     *     contact:bool,
     *     profiles:bool,
     *     work:bool,
     *     education:bool,
     *     skills:bool,
     *     languages:bool
     * } $options
     * @return string
     */
    public function toMarkdown(array $options = [
        'basics' => true,
        'contact' => true,
        'profiles' => true,
        'work' => true,
        'education' => true,
        'skills' => true,
        'languages' => true,
    ]): string
    {
        $options = array_replace_recursive([
            'basics' => true,
            'contact' => true,
            'profiles' => true,
            'work' => true,
            'education' => true,
            'skills' => true,
            'languages' => true,
        ], $options);

        $md = [];

        // Basics
        if ($options['basics']) {
            $md[] = "# {$this->basics->name}";
            $md[] = "**{$this->basics->label}**";
            if ( ! empty($this->basics->summary)) {
                $md[] = $this->basics->summary;
            }
            $md[] = '';
        }

        // Contact Info
        if ($options['contact']) {
            $md[] = "📧 Email: [{$this->basics->email}](mailto:{$this->basics->email})";
            $md[] = "🌍 Website: [{$this->basics->url}]({$this->basics->url})";
            if ( ! empty($this->basics->location)) {
                $location = "{$this->basics->location->city}, {$this->basics->location->countryCode}";
                $md[] = "📍 Location: {$location}";
            }
        }

        // Profiles
        if ($options['profiles'] && ! empty($this->basics->profiles)) {
            $md[] = "\n### 🔗 Social Profiles";
            foreach ($this->basics->profiles as $profile) {
                $md[] = "- [{$profile->network->value}]({$profile->url})";
            }
        }

        // Work Experience
        if ($options['work'] && ! empty($this->work)) {
            $md[] = "\n## 💼 Work Experience";
            foreach ($this->work as $job) {
                $startDate = $job->startDate?->format('Y-m') ?? 'Present';
                $endDate = $job->endDate?->format('Y-m') ?? 'Present';
                $md[] = "### {$job->position} at {$job->name}";
                $md[] = "_{$startDate} → {$endDate}_";
                if ( ! empty($job->summary)) {
                    $md[] = $job->summary;
                }
                foreach ($job->highlights as $highlight) {
                    $md[] = "- {$highlight}";
                }
                $md[] = '';
            }
        }

        // Education
        if ($options['education'] && ! empty($this->education)) {
            $md[] = "\n## 🎓 Education";
            foreach ($this->education as $edu) {
                $startDate = $edu->startDate?->format('Y-m') ?? 'Present';
                $endDate = $edu->endDate?->format('Y-m') ?? 'Present';
                $md[] = "### {$edu->institution}";
                $md[] = "_{$startDate} → {$endDate}_";
                $md[] = "{$edu->area} in {$edu->studyType?->value}";
                $md[] = '';
            }
        }

        // Skills
        if ($options['skills'] && ! empty($this->skills)) {
            $md[] = "\n## 🛠 Skills";
            foreach ($this->skills as $skill) {
                $md[] = "- **{$skill->name}**: " . implode(', ', $skill->keywords);
            }
        }

        // Languages
        if ($options['languages'] && ! empty($this->languages)) {
            $md[] = "\n## 🌍 Languages";
            foreach ($this->languages as $lang) {
                $md[] = "- {$lang->language} ({$lang->fluency})";
            }
        }

        return implode("\n", array_filter($md));
    }
}
