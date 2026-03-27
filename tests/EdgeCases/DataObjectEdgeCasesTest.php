<?php

declare(strict_types=1);

namespace Tests\EdgeCases;

use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use PHPUnit\Framework\TestCase;

final class DataObjectEdgeCasesTest extends TestCase
{
    public function testBasicsWithLongStrings(): void
    {
        $longString = str_repeat('a', 1000);
        $basics = new Basics(
            name: $longString,
            label: $longString,
            email: new Email('john@example.com'),
            url: new Url('https://example.com'),
            summary: $longString,
            location: new Location(
                address: $longString,
                postalCode: '12345',
                city: $longString,
                countryCode: 'US',
                region: $longString,
            ),
        );

        $this->assertEquals($longString, $basics->name);
    }
}
