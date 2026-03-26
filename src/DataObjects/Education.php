<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Concerns\ValidatesDate;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Education implements JsonSerializable
{
    use ValidatesDate;

    /**
     * @param string $institution
     * @param Url|null $url
     * @param string|null $area
     * @param EducationLevel|null $studyType
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $score
     * @param list<string> $courses
     */
    public function __construct(
        #[Field('institution')]
        public string $institution,
        #[Field('url')]
        public ?Url $url = null,
        #[Field('area')]
        public ?string $area = null,
        #[Field('studyType')]
        public ?EducationLevel $studyType = null,
        #[Field('startDate')]
        public ?string $startDate = null,
        #[Field('endDate')]
        public ?string $endDate = null,
        #[Field('score')]
        public ?string $score = null,
        #[Field('courses')]
        public array $courses = [],
    ) {
        if (null !== $this->startDate) {
            $this->assertDate($this->startDate);
        }

        if (null !== $this->endDate) {
            $this->assertDate($this->endDate);
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
            'url' => $this->url?->jsonSerialize(),
            'area' => $this->area,
            'studyType' => $this->studyType?->value,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'score' => $this->score,
            'courses' => $this->courses,
        ];
    }
}
