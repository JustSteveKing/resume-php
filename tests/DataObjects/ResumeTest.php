<?php

declare(strict_types=1);

namespace Tests\DataObjects;

use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use Tests\PackageTestCase;

final class ResumeTest extends PackageTestCase
{
    public function test_it_outputs_full_markdown(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new \JustSteveKing\Resume\Exporters\MarkdownExporter();
        $markdown = $exporter->export($resume);

        $this->assertStringContainsString('# John Doe', $markdown);
        $this->assertStringContainsString('**Software Engineer**', $markdown);
        $this->assertStringContainsString('## 💼 Work Experience', $markdown);
        $this->assertStringContainsString('## 🎓 Education', $markdown);
        $this->assertStringContainsString('## 🛠 Skills', $markdown);
    }

    public function test_it_can_exclude_work_section(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new \JustSteveKing\Resume\Exporters\MarkdownExporter();
        $markdown = $exporter->export($resume, ['work' => false]);

        $this->assertStringContainsString('# John Doe', $markdown);
        $this->assertStringNotContainsString('## 💼 Work Experience', $markdown);
    }

    public function test_it_can_output_only_basics(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new \JustSteveKing\Resume\Exporters\MarkdownExporter();
        $markdown = $exporter->export($resume, [
            'work' => false,
            'education' => false,
            'skills' => false,
            'languages' => false,
        ]);

        $this->assertStringContainsString('# John Doe', $markdown);
        $this->assertStringNotContainsString('## 💼 Work Experience', $markdown);
        $this->assertStringNotContainsString('## 🎓 Education', $markdown);
    }

    public function test_it_handles_empty_sections_gracefully(): void
    {
        $resume = new Resume(
            basics: new Basics(
                name: 'Jane Doe',
                label: 'Designer',
                email: new Email('jane@example.com'),
                url: new Url('https://janedoe.com'),
                profiles: [
                    new Profile(Network::Instagram, 'janedoe', new Url('https://instagram.com/janedoe')),
                ],
            ),
        );

        $exporter = new \JustSteveKing\Resume\Exporters\MarkdownExporter();
        $markdown = $exporter->export($resume);

        $this->assertStringContainsString('# Jane Doe', $markdown);
        $this->assertStringNotContainsString('## 💼 Work Experience', $markdown);
        $this->assertStringNotContainsString('## 🎓 Education', $markdown);
    }

    public function test_it_outputs_social_profiles(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new \JustSteveKing\Resume\Exporters\MarkdownExporter();
        $markdown = $exporter->export($resume);

        $this->assertStringContainsString('### 🔗 Social Profiles', $markdown);
        $this->assertStringContainsString('- [github](https://github.com/johndoe)', $markdown);
        $this->assertStringContainsString('- [linkedin](https://linkedin.com/in/johndoe)', $markdown);
    }

    public function test_transform_returns_correct_json_ld_structure(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Developer',
            email: new Email('john@example.com'),
            url: new Url('https://johndoe.com'),
            profiles: [
                new Profile(Network::GitHub, 'johndoe', new Url('https://github.com/johndoe')),
            ],
        );

        $resume = new Resume(basics: $basics);
        $exporter = new \JustSteveKing\Resume\Exporters\JsonLdExporter();
        $jsonLd = $exporter->export($resume);

        $this->assertSame('https://schema.org', $jsonLd['@context']);
        $this->assertSame('Person', $jsonLd['@type']);
        $this->assertSame('John Doe', $jsonLd['name']);
        $this->assertSame('https://johndoe.com', $jsonLd['url']);
        $this->assertSame('Developer', $jsonLd['jobTitle']);
        $this->assertContains('https://github.com/johndoe', $jsonLd['sameAs']);
    }

    public function test_transform_handles_missing_profiles_and_skills(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Developer',
            email: new Email('john@example.com'),
            url: new Url('https://johndoe.com'),
        );

        $resume = new Resume(basics: $basics);
        $exporter = new \JustSteveKing\Resume\Exporters\JsonLdExporter();
        $jsonLd = $exporter->export($resume);

        $this->assertEmpty($jsonLd['sameAs']);
        $this->assertEmpty($jsonLd['knowsAbout']);
    }
}
