<?php

declare(strict_types=1);

namespace Tests\Exporters;

use JustSteveKing\Resume\Exporters\YamlExporter;
use JustSteveKing\Resume\Factories\ResumeFactory;
use Tests\PackageTestCase;

final class YamlExporterTest extends PackageTestCase
{
    public function testItCanExportToYaml(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new YamlExporter();

        $yaml = $exporter->export($resume);

        $this->assertIsString($yaml);
        $this->assertStringContainsString('basics:', $yaml);
        $this->assertStringContainsString('John Doe', $yaml);
    }

    public function testItCanHydrateFromExportedYaml(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new YamlExporter();

        $yaml = $exporter->export($resume);
        $hydrated = ResumeFactory::fromYaml($yaml);

        $this->assertSame($resume->basics->name, $hydrated->basics->name);
        $this->assertSame($resume->basics->email->value, $hydrated->basics->email->value);
        $this->assertCount(count($resume->work), $hydrated->work);
    }
}
