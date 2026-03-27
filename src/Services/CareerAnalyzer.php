<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Services;

use DateTimeImmutable;
use JustSteveKing\Resume\DataObjects\Resume;

final readonly class CareerAnalyzer
{
    public function __construct(
        private Resume $resume,
    ) {}

    /**
     * Calculate total years of work experience.
     *
     * @return float
     */
    public function getTotalYearsExperience(): float
    {
        $totalDays = 0;

        foreach ($this->resume->work as $work) {
            $start = $work->startDate ?? new DateTimeImmutable();
            $end = $work->endDate ?? new DateTimeImmutable();
            
            $diff = $start->diff($end);
            $totalDays += (int) $diff->format('%a');
        }

        return round($totalDays / 365.25, 1);
    }

    /**
     * Get unique job titles held.
     *
     * @return list<string>
     */
    public function getUniqueJobTitles(): array
    {
        return array_values(array_unique(array_map(
            fn($work) => $work->position,
            $this->resume->work
        )));
    }

    /**
     * Analyze skill usage frequency based on mentions in work highlights.
     *
     * @return array<string, int>
     */
    public function getSkillFrequency(): array
    {
        $frequencies = [];
        $skillNames = array_map(fn($skill) => strtolower($skill->name), $this->resume->skills);

        foreach ($this->resume->work as $work) {
            $text = strtolower($work->summary . ' ' . implode(' ', $work->highlights));
            
            foreach ($skillNames as $skill) {
                if (str_contains($text, $skill)) {
                    $frequencies[$skill] = ($frequencies[$skill] ?? 0) + 1;
                }
            }
        }

        arsort($frequencies);
        return $frequencies;
    }

    /**
     * Identify gaps in work history (greater than 30 days).
     *
     * @return array<int, array{start: string, end: string, days: int}>
     */
    public function getWorkGaps(): array
    {
        $workEntries = $this->resume->work;
        
        // Sort by start date
        usort($workEntries, function($a, $b) {
            $aStart = $a->startDate ?? new DateTimeImmutable();
            $bStart = $b->startDate ?? new DateTimeImmutable();
            return $aStart <=> $bStart;
        });

        $gaps = [];
        $previousEnd = null;

        foreach ($workEntries as $work) {
            $currentStart = $work->startDate;
            
            if ($previousEnd !== null && $currentStart !== null) {
                $diff = $previousEnd->diff($currentStart);
                $days = (int) $diff->format('%r%a');
                
                if ($days > 30) {
                    $gaps[] = [
                        'start' => $previousEnd->format('Y-m-d'),
                        'end' => $currentStart->format('Y-m-d'),
                        'days' => $days,
                    ];
                }
            }
            
            $currentEnd = $work->endDate ?? new DateTimeImmutable();
            if ($previousEnd === null || $currentEnd > $previousEnd) {
                $previousEnd = $currentEnd;
            }
        }

        return $gaps;
    }
}
