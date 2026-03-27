<?php

declare(strict_types=1);

namespace Tests\Exporters;

use JustSteveKing\Resume\Exporters\MarkdownExporter;
use Tests\PackageTestCase;

final class MarkdownExporterI18nTest extends PackageTestCase
{
    public function testItCanExportInEnglish(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new MarkdownExporter(locale: 'en');
        
        $md = $exporter->export($resume);
        
        $this->assertStringContainsString('## 💼 Work Experience', $md);
        $this->assertStringContainsString('📧 Email:', $md);
    }

    public function testItCanExportInWelsh(): void
    {
        $resume = $this->buildCompleteResume();
        $exporter = new MarkdownExporter(locale: 'cy');
        
        $md = $exporter->export($resume);
        
        $this->assertStringContainsString('## 💼 Profiad Gwaith', $md);
        $this->assertStringContainsString('📧 E-bost:', $md);
    }
}
