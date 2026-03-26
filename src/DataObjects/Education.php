<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use DateTimeImmutable;
use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Education implements JsonSerializable
{
    public ?DateTimeImmutable $startDate;
    public ?DateTimeImmutable $endDate;

    /**
     * @param string $institution
     * @param Url|null $url
     * @param string|null $area
     * @param EducationLevel|null $studyType
     * @param string|DateTimeImmutable|null $startDate
     * @param string|DateTimeImmutable|null $endDate
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
        string|DateTimeImmutable|null $startDate = null,
        #[Field('endDate')]
        string|DateTimeImmutable|null $endDate = null,
        #[Field('score')]
        public ?string $score = null,
        #[Field('courses')]
        public array $courses = [],
    ) {
        $this->startDate = is_string($startDate) ? new DateTimeImmutable($startDate) : $startDate;
        $this->endDate = is_string($endDate) ? new DateTimeImmutable($endDate) : $endDate;
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
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'score' => $this->score,
            'courses' => $this->courses,
        ];
    }
}
