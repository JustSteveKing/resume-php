<?php

declare(strict_types=1);

namespace Tests\Factories;

use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Factories\ResumeFactory;
use Tests\PackageTestCase;

final class ResumeFactoryTest extends PackageTestCase
{
    public function test_it_can_hydrate_from_array(): void
    {
        $json = file_get_contents(__DIR__ . '/../../example.resume.json');
        $data = json_decode($json, true);

        // Before hydrating, let's fix the network if it's invalid
        // or we should update the enum. Let's see if it works without fixing it.
        // Actually, I'll fix it here for now if needed, but I'll try without first.
        
        $resume = ResumeFactory::fromArray($data);

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertSame('Spock', $resume->basics->name);
        $this->assertCount(1, $resume->work);
        $this->assertSame('Starfleet Command', $resume->work[0]->name);
        $this->assertCount(2, $resume->skills);
        $this->assertCount(1, $resume->education);
        $this->assertCount(2, $resume->languages);
        $this->assertCount(2, $resume->interests);
        $this->assertCount(1, $resume->projects);
    }

    public function test_it_can_hydrate_from_json(): void
    {
        $json = file_get_contents(__DIR__ . '/../../example.resume.json');
        
        $resume = ResumeFactory::fromJson($json);

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertSame('Spock', $resume->basics->name);
    }

    public function test_it_throws_hydration_exception_for_missing_basics(): void
    {
        $this->expectException(\JustSteveKing\Resume\Exceptions\HydrationException::class);
        $this->expectExceptionMessage('Missing basics section');

        ResumeFactory::fromArray([]);
    }

    public function test_it_throws_hydration_exception_for_invalid_json(): void
    {
        $this->expectException(\JustSteveKing\Resume\Exceptions\HydrationException::class);
        $this->expectExceptionMessage('Invalid JSON provided');

        ResumeFactory::fromJson('invalid-json');
    }
}
