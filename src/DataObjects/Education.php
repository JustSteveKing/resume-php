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
<<<<<<< HEAD
     * @param string|DateTimeImmutable|null $startDate
     * @param string|DateTimeImmutable|null $endDate
=======
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
>>>>>>> feature/typed-dates
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
<<<<<<< HEAD
        string|DateTimeImmutable|null $startDate = null,
        #[Field('endDate')]
        string|DateTimeImmutable|null $endDate = null,
=======
        public ?\DateTimeImmutable $startDate = null,
        #[Field('endDate')]
        public ?\DateTimeImmutable $endDate = null,
>>>>>>> feature/typed-dates
        #[Field('score')]
        public ?string $score = null,
        #[Field('courses')]
        public array $courses = [],
    ) {
<<<<<<< HEAD
        $this->startDate = is_string($startDate) ? new DateTimeImmutable($startDate) : $startDate;
        $this->endDate = is_string($endDate) ? new DateTimeImmutable($endDate) : $endDate;
=======
        if (null !== $this->url) {
            $this->assertUrl($this->url);
        }
>>>>>>> feature/typed-dates
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
