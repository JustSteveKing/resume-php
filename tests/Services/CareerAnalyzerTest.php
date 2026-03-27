<?php

declare(strict_types=1);

namespace Tests\Services;

use DateTimeImmutable;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use Tests\PackageTestCase;

final class CareerAnalyzerTest extends PackageTestCase
{
    public function testItCalculatesTotalExperience(): void
    {
        $resume = new Resume(
            basics: new Basics(name: 'Test', label: 'Tester'),
            work: [
                new Work(
                    name: 'Company A',
                    position: 'Dev',
                    startDate: new DateTimeImmutable('2020-01-01'),
                    endDate: new DateTimeImmutable('2021-01-01'), // 1 year
                ),
                new Work(
                    name: 'Company B',
                    position: 'Dev',
                    startDate: new DateTimeImmutable('2022-01-01'),
                    endDate: new DateTimeImmutable('2024-01-01'), // 2 years
                ),
            ],
        );

        $analyzer = $resume->getInsights();

        // 3.0 years (approximately, allowing for leap years / roundings)
        $this->assertEquals(3.0, $analyzer->getTotalYearsExperience());
    }

    public function testItAnalyzesSkillFrequency(): void
    {
        $resume = new Resume(
            basics: new Basics(name: 'Test', label: 'Tester'),
            work: [
                new Work(
                    name: 'A',
                    position: 'P',
                    highlights: ['Experienced in PHP and Laravel'],
                ),
                new Work(
                    name: 'B',
                    position: 'P',
                    highlights: ['Built APIs with PHP'],
                ),
            ],
            skills: [
                new Skill(name: 'PHP'),
                new Skill(name: 'Laravel'),
                new Skill(name: 'Python'),
            ],
        );

        $analyzer = $resume->getInsights();
        $freq = $analyzer->getSkillFrequency();

        $this->assertSame(2, $freq['php']);
        $this->assertSame(1, $freq['laravel']);
        $this->assertArrayNotHasKey('python', $freq);
    }

    public function testItIdentifiesWorkGaps(): void
    {
        $resume = new Resume(
            basics: new Basics(name: 'Test', label: 'Tester'),
            work: [
                new Work(
                    name: 'Job 1',
                    position: 'P',
                    startDate: new DateTimeImmutable('2020-01-01'),
                    endDate: new DateTimeImmutable('2020-06-01'),
                ),
                new Work(
                    name: 'Job 2',
                    position: 'P',
                    startDate: new DateTimeImmutable('2020-08-01'), // 2 month gap
                    endDate: new DateTimeImmutable('2021-01-01'),
                ),
            ],
        );

        $analyzer = $resume->getInsights();
        $gaps = $analyzer->getWorkGaps();

        $this->assertCount(1, $gaps);
        $this->assertSame('2020-06-01', $gaps[0]['start']);
        $this->assertSame('2020-08-01', $gaps[0]['end']);
        $this->assertGreaterThan(30, $gaps[0]['days']);
    }
}
