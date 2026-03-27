<?php

declare(strict_types=1);

namespace Tests\Services;

use JustSteveKing\Resume\Services\Translator;
use Tests\PackageTestCase;

final class TranslatorTest extends PackageTestCase
{
    public function testItCanTranslateToEnglish(): void
    {
        $translator = Translator::getInstance('en');
        $translator->setLocale('en');
        
        $this->assertSame('Work Experience', $translator->trans('sections.work'));
        $this->assertSame('Email', $translator->trans('contact.email'));
    }

    public function testItCanTranslateToWelsh(): void
    {
        $translator = Translator::getInstance('en');
        $translator->setLocale('cy');
        
        $this->assertSame('Profiad Gwaith', $translator->trans('sections.work'));
        $this->assertSame('E-bost', $translator->trans('contact.email'));
    }

    public function testSingletonBehavior(): void
    {
        $translator1 = Translator::getInstance('en');
        $translator2 = Translator::getInstance('cy');
        
        $this->assertSame($translator1, $translator2);
    }
}
