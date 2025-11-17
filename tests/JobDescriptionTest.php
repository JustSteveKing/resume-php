<?php

declare(strict_types=1);

namespace Tests;

use JustSteveKing\Resume\Builders\JobDescriptionBuilder;
use JustSteveKing\Resume\DataObjects\JobDescription;

final class JobDescriptionTest extends PackageTestCase
{
    public function testBuilderCreatesCorrectInstanceFromData(): void
    {
        $data = [
            'name' => 'Backend Engineer',
            'location' => 'San Francisco',
            'description' => 'Developed backend systems.',
            'highlights' => ['Built REST APIs', 'Optimized queries'],
            'skills' => ['PHP', 'MySQL'],
            'tools' => ['Docker', 'Git'],
            'responsibilities' => ['Code reviews', 'Mentorship'],
            'deliverables' => ['API documentation', 'Unit tests'],
        ];

        $builder = new JobDescriptionBuilder();
        $jobDesc = $builder
            ->name($data['name'])
            ->location($data['location'])
            ->description($data['description'])
            ->highlights($data['highlights'])
            ->skills($data['skills'])
            ->tools($data['tools'])
            ->responsibilities($data['responsibilities'])
            ->deliverables($data['deliverables'])
            ->build();

        $this->assertInstanceOf(JobDescription::class, $jobDesc);
        $this->assertSame('Backend Engineer', $jobDesc->name);
        $this->assertSame('Developed backend systems.', $jobDesc->description);
        $this->assertCount(2, $jobDesc->highlights);
    }

    public function testBuilderCreatesCorrectInstance(): void
    {
        $builder = new JobDescriptionBuilder();

        $jobDesc = $builder
            ->name('Frontend Developer')
            ->description('Created frontend UI')
            ->addHighlight('React migration')
            ->addSkill('JavaScript')
            ->addTool('Webpack')
            ->addResponsibility('UI testing')
            ->addDeliverable('User guide')
            ->build();

        $this->assertSame('Frontend Developer', $jobDesc->name);
        $this->assertEquals(['React migration'], $jobDesc->highlights);
    }

    public function testBuilderHandlesEmptyValues(): void
    {
        $builder = new JobDescriptionBuilder();

        $jobDesc = $builder
            ->name('Empty Fields')
            ->location(null)
            ->description(null)
            ->highlights([])
            ->skills([])
            ->tools([])
            ->responsibilities([])
            ->deliverables([])
            ->build();

        $this->assertSame('Empty Fields', $jobDesc->name);
        $this->assertNull($jobDesc->description);
        $this->assertEmpty($jobDesc->highlights);
    }
}
