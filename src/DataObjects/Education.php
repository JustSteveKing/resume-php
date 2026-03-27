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
     *     url?: string,
     *     area?: string,
     *     studyType?: string,
     *     startDate?: string,
     *     endDate?: string,
     *     score?: string,
     *     courses?: list<string>
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'institution' => $this->institution,
        ];

        if (null !== $this->url) {
            $data['url'] = $this->url->jsonSerialize();
        }
        if (null !== $this->area) {
            $data['area'] = $this->area;
        }
        if (null !== $this->studyType) {
            $data['studyType'] = $this->studyType->value;
        }
        if (null !== $this->startDate) {
            $data['startDate'] = $this->startDate->format('Y-m-d');
        }
        if (null !== $this->endDate) {
            $data['endDate'] = $this->endDate->format('Y-m-d');
        }
        if (null !== $this->score) {
            $data['score'] = $this->score;
        }
        if ( ! empty($this->courses)) {
            $data['courses'] = $this->courses;
        }

        return $data;
    }
}
