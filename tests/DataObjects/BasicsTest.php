<?php

declare(strict_types=1);

namespace Tests\DataObjects;

use InvalidArgumentException;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\PackageTestCase;

final class BasicsTest extends PackageTestCase
{
    public static function invalidEmailProvider(): array
    {
        return [
            'empty string' => [''],
            'no at symbol' => ['invalidemail'],
            'multiple at symbols' => ['invalid@@email.com'],
            'no domain' => ['invalid@'],
            'no local part' => ['@example.com'],
            'invalid characters' => ['invalid email@example.com'],
            'too many dots' => ['invalid..email@example.com'],
            'starts with dot' => ['.invalid@example.com'],
            'ends with dot' => ['invalid.@example.com'],
            'no tld' => ['invalid@example'],
        ];
    }
    #[Test]
    public function can_create_basics_with_required_fields(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
        );

        $this->assertSame('John Doe', $basics->name);
        $this->assertSame('Software Engineer', $basics->label);
        $this->assertNull($basics->email);
        $this->assertNull($basics->phone);
        $this->assertNull($basics->url);
        $this->assertNull($basics->summary);
        $this->assertNull($basics->location);
        $this->assertSame([], $basics->profiles);
    }

    #[Test]
    public function can_create_basics_with_all_fields(): void
    {
        $location = new Location(
            address: '123 Main St',
            postalCode: '94105',
            city: 'San Francisco',
            countryCode: 'US',
            region: 'CA',
        );

        $profile = new Profile(
            network: Network::GitHub,
            username: 'johndoe',
            url: new Url('https://github.com/johndoe'),
        );

        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: new Email('john@example.com'),
            phone: '+1-555-123-4567',
            url: new Url('https://johndoe.com'),
            summary: 'Experienced software engineer with 5+ years in web development.',
            location: $location,
            profiles: [$profile],
        );

        $this->assertSame('John Doe', $basics->name);
        $this->assertSame('Software Engineer', $basics->label);
        $this->assertSame('john@example.com', $basics->email->value);
        $this->assertSame('+1-555-123-4567', $basics->phone);
        $this->assertSame('https://johndoe.com', $basics->url->value);
        $this->assertSame('Experienced software engineer with 5+ years in web development.', $basics->summary);
        $this->assertSame($location, $basics->location);
        $this->assertSame([$profile], $basics->profiles);
    }

    #[Test]
    public function accepts_null_email(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: null,
        );

        $this->assertNull($basics->email);
    }

    #[Test]
    public function validates_valid_email(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: new Email('john@example.com'),
        );

        $this->assertSame('john@example.com', $basics->email->value);
    }

    #[Test]
    #[DataProvider('invalidEmailProvider')]
    public function throws_exception_for_invalid_email(string $invalidEmail): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($invalidEmail);
    }

    #[Test]
    public function json_serialization_with_minimal_data(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
        );

        $expected = [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'profiles' => [],
        ];

        $this->assertSame($expected, $basics->jsonSerialize());
    }

    #[Test]
    public function json_serialization_with_full_data(): void
    {
        $location = new Location(
            address: '123 Main St',
            postalCode: '94105',
            city: 'San Francisco',
            countryCode: 'US',
            region: 'CA',
        );

        $profile = new Profile(
            network: Network::GitHub,
            username: 'johndoe',
            url: new Url('https://github.com/johndoe'),
        );

        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: new Email('john@example.com'),
            phone: '+1-555-123-4567',
            url: new Url('https://johndoe.com'),
            summary: 'Experienced software engineer.',
            location: $location,
            profiles: [$profile],
        );

        $expected = [
            'name' => 'John Doe',
            'label' => 'Software Engineer',
            'email' => 'john@example.com',
            'phone' => '+1-555-123-4567',
            'url' => 'https://johndoe.com',
            'summary' => 'Experienced software engineer.',
            'location' => [
                'address' => '123 Main St',
                'postalCode' => '94105',
                'city' => 'San Francisco',
                'countryCode' => 'US',
                'region' => 'CA',
            ],
            'profiles' => [
                [
                    'network' => 'github',
                    'username' => 'johndoe',
                    'url' => 'https://github.com/johndoe',
                ],
            ],
        ];

        $this->assertSame($expected, $basics->jsonSerialize());
    }

    #[Test]
    public function json_serialization_filters_null_values(): void
    {
        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: new Email('john@example.com'),
            phone: null,
            url: null,
            summary: null,
            location: null,
            profiles: [],
        );

        $result = $basics->jsonSerialize();

        $this->assertArrayNotHasKey('phone', $result);
        $this->assertArrayNotHasKey('url', $result);
        $this->assertArrayNotHasKey('summary', $result);
        $this->assertArrayNotHasKey('location', $result);
        $this->assertArrayHasKey('profiles', $result); // Empty array should be included
        $this->assertSame([], $result['profiles']);
    }
}
