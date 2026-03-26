<?php

declare(strict_types=1);

namespace Tests\Builders;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use Tests\PackageTestCase;

final class EnhancedBuilderTest extends PackageTestCase
{
    public function testItSupportsNestedFluentCalls(): void
    {
        $builder = new ResumeBuilder();

        $resume = $builder->basics()
            ->name('John Doe')
            ->label('Developer')
            ->email('john@example.com')
            ->location()
                ->city('San Francisco')
                ->countryCode('US')
            ->end()
        ->end()
        ->addWork()
            ->name('Acme Corp')
            ->position('Senior Developer')
        ->end()
        ->build();

        $this->assertEquals('John Doe', $resume->basics->name);
        $this->assertEquals('San Francisco', $resume->basics->location->city);
        $this->assertCount(1, $resume->work);
        $this->assertEquals('Acme Corp', $resume->work[0]->name);
    }
}
