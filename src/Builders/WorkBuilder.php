<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Builders;

use DateTimeImmutable;
use JustSteveKing\Resume\DataObjects\Work;

final class WorkBuilder
{
    private string $name;
    private string $position;
    private ?string $location = null;
    private ?string $url = null;
    private string|DateTimeImmutable|null $startDate = null;
    private string|DateTimeImmutable|null $endDate = null;
    private ?string $summary = null;
    /** @var list<string> $highlights */
    private array $highlights = [];

    public function __construct(private readonly ResumeBuilder $parent) {}

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function position(string $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function location(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function startDate(string|DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function endDate(string|DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function summary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function addHighlight(string $highlight): self
    {
        $this->highlights[] = $highlight;
        return $this;
    }

    public function end(): ResumeBuilder
    {
        return $this->parent;
    }

    public function build(): Work
    {
        return new Work(
            name: $this->name,
            position: $this->position,
            location: $this->location,
            url: $this->url,
            startDate: $this->startDate,
            endDate: $this->endDate,
            summary: $this->summary,
            highlights: $this->highlights,
        );
    }
}
