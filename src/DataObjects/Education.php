<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\Concerns\ValidatesUrl;
use JustSteveKing\Resume\Enums\EducationLevel;

final readonly class Education implements JsonSerializable
{
    use ValidatesDate;
    use ValidatesUrl;

    /**
     * @param string $institution
     * @param string|null $url
     * @param string|null $area
     * @param EducationLevel|null $studyType
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
     * @param string|null $score
     * @param list<string> $courses
     */
    public function __construct(
        #[Field('institution')]
        public string $institution,
        #[Field('url')]
        public ?string $url = null,
        #[Field('area')]
        public ?string $area = null,
        #[Field('studyType')]
        public ?EducationLevel $studyType = null,
        #[Field('startDate')]
        public ?\DateTimeImmutable $startDate = null,
        #[Field('endDate')]
        public ?\DateTimeImmutable $endDate = null,
        #[Field('score')]
        public ?string $score = null,
        #[Field('courses')]
        public array $courses = [],
    ) {
        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
    }

    /**
     * Convert the Education instance to an array for JSON serialization.
     *
     * @return array{
     *     institution: string,
     *     url?: string|null,
     *     area?: string|null,
     *     studyType?: string|null,
     *     startDate?: string|null,
     *     endDate?: string|null,
     *     score?: string|null,
     *     courses: list<string>
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'institution' => $this->institution,
            'url' => $this->url,
            'area' => $this->area,
            'studyType' => $this->studyType?->value,
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'score' => $this->score,
            'courses' => $this->courses,
        ];
    }
}
