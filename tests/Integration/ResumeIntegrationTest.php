<?php

declare(strict_types=1);

namespace Tests\Integration;

use DateTimeImmutable;
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use Tests\PackageTestCase;

final class ResumeIntegrationTest extends PackageTestCase
{
    public function testCompleteResumeWorkflow(): void
    {
        $resume = $this->buildCompleteResume();

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertSame('John Doe', $resume->basics->name);
        $this->assertCount(2, $resume->work);
        $this->assertCount(1, $resume->education);
        $this->assertCount(3, $resume->skills);
        $this->assertCount(1, $resume->projects);
    }

    public function testResumeSummaryWorkflow(): void
    {
        $resume = $this->buildCompleteResume();
        $summary = $resume->getSummary();

        $this->assertSame('John Doe', $summary['name']);
        $this->assertSame('john@example.com', $summary['email']);
        $this->assertSame(2, $summary['work_experiences']);
        $this->assertSame(1, $summary['education_entries']);
        $this->assertSame(3, $summary['skills']);
    }

    public function testJsonResumeSchemaCompliance(): void
    {
        $resume = $this->buildCompleteResume();
        $json = json_encode($resume, JSON_THROW_ON_ERROR);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('basics', $data);
        $this->assertArrayHasKey('work', $data);
        $this->assertArrayHasKey('education', $data);
        $this->assertArrayHasKey('skills', $data);
        $this->assertSame('https://jsonresume.org/schema/schema.json', $data['$schema']);
        
        // Ensure no null values are present in the serialized output
        $this->assertNoNullValues($data);
    }

    /**
     * Recursive helper to ensure no null values exist in the array.
     * 
     * @param array<string, mixed> $data
     */
    private function assertNoNullValues(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->assertNotNull($value, "Found null value for key: {$key}");
            if (is_array($value)) {
                $this->assertNoNullValues($value);
            }
        }
    }

    public function testPerformanceWithLargeResume(): void
    {
        $builder = new ResumeBuilder();
        $builder->basics(new Basics(
            name: 'John Doe',
            label: 'Developer',
            email: new Email('john@example.com'),
            url: new Url('https://johndoe.com'),
        ));

        for ($i = 0; $i < 100; $i++) {
            $builder->addWork(new Work(
                name: "Company $i",
                position: 'Developer',
                startDate: new DateTimeImmutable('2020-01-01'),
            ));
        }

        $start = microtime(true);
        $resume = $builder->build();
        $json = json_encode($resume, JSON_THROW_ON_ERROR);
        $end = microtime(true);

        $this->assertLessThan(0.1, $end - $start);
        $this->assertCount(100, $resume->work);
    }
}
