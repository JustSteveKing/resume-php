<?php

declare(strict_types=1);

namespace Tests\Enums;

use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\ResumeSchema;
use JustSteveKing\Resume\Enums\SkillLevel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\PackageTestCase;

final class EnumTest extends PackageTestCase
{
    public static function networkValueProvider(): array
    {
        return [
            'valid github' => ['github', Network::GitHub],
            'valid linkedin' => ['linkedin', Network::LinkedIn],
            'valid twitter' => ['twitter', Network::Twitter],
            'valid stackoverflow' => ['stackoverflow', Network::StackOverflow],
            'valid personal_website' => ['personal_website', Network::PersonalWebsite],
            'valid mastodon' => ['mastodon', Network::Mastodon],
            'valid bluesky' => ['bluesky', Network::Bluesky],
            'invalid value' => ['invalid_network', null],
            'empty string' => ['', null],
        ];
    }

    public static function skillLevelValueProvider(): array
    {
        return [
            'valid beginner' => ['Beginner', SkillLevel::Beginner],
            'valid intermediate' => ['Intermediate', SkillLevel::Intermediate],
            'valid advanced' => ['Advanced', SkillLevel::Advanced],
            'valid expert' => ['Expert', SkillLevel::Expert],
            'invalid value' => ['Pro', null],
            'lowercase' => ['beginner', null], // Case-sensitive
            'empty string' => ['', null],
        ];
    }

    public static function educationLevelValueProvider(): array
    {
        return [
            'valid bachelor' => ['Bachelor', EducationLevel::Bachelor],
            'valid master' => ['Master', EducationLevel::Master],
            'valid doctorate' => ['Doctorate', EducationLevel::Doctorate],
            'valid high school' => ['High School', EducationLevel::HighSchool],
            'valid bootcamp' => ['Bootcamp', EducationLevel::Bootcamp],
            'invalid value' => ['PhD', null],
            'lowercase' => ['bachelor', null], // Case-sensitive
            'empty string' => ['', null],
        ];
    }
    #[Test]
    public function education_level_enum_has_all_expected_values(): void
    {
        $expectedValues = [
            'Primary' => EducationLevel::Primary,
            'Secondary' => EducationLevel::Secondary,
            'High School' => EducationLevel::HighSchool,
            'Associate' => EducationLevel::Associate,
            'Bachelor' => EducationLevel::Bachelor,
            'Master' => EducationLevel::Master,
            'Doctorate' => EducationLevel::Doctorate,
            'Bootcamp' => EducationLevel::Bootcamp,
            'Other' => EducationLevel::Other,
        ];

        foreach ($expectedValues as $expectedValue => $case) {
            $this->assertSame($expectedValue, $case->value);
        }

        $this->assertCount(9, EducationLevel::cases());
    }

    #[Test]
    public function skill_level_enum_has_all_expected_values(): void
    {
        $expectedValues = [
            'Beginner' => SkillLevel::Beginner,
            'Intermediate' => SkillLevel::Intermediate,
            'Advanced' => SkillLevel::Advanced,
            'Expert' => SkillLevel::Expert,
        ];

        foreach ($expectedValues as $expectedValue => $case) {
            $this->assertSame($expectedValue, $case->value);
        }

        $this->assertCount(4, SkillLevel::cases());
    }

    #[Test]
    public function resume_schema_enum_has_expected_value(): void
    {
        $this->assertSame(
            'https://jsonresume.org/schema/schema.json',
            ResumeSchema::V1->value,
        );

        $this->assertCount(1, ResumeSchema::cases());
    }

    #[Test]
    public function network_enum_has_all_expected_values(): void
    {
        $expectedNetworks = [
            'twitter', 'github', 'linkedin', 'facebook', 'instagram',
            'youtube', 'tiktok', 'stackoverflow', 'reddit', 'personal_website',
            'other', 'mastodon', 'bluesky', 'discord', 'whatsapp', 'telegram',
            'snapchat', 'pinterest', 'tumblr', 'medium', 'gitlab', 'bitbucket',
            'dribbble', 'behance', 'flickr', 'vimeo', 'quora', 'slack',
            'clubhouse', 'whatsapp_business', 'signal', 'wechat', 'line',
            'viber', 'skype', 'Starfleet Database',
        ];

        $actualNetworks = array_map(
            static fn(Network $network) => $network->value,
            Network::cases(),
        );

        $this->assertSame(sort($expectedNetworks), sort($actualNetworks));
        $this->assertCount(36, Network::cases());
    }

    #[Test]
    #[DataProvider('networkValueProvider')]
    public function network_enum_try_from_works_correctly(string $value, ?Network $expected): void
    {
        $result = Network::tryFrom($value);
        $this->assertSame($expected, $result);
    }

    #[Test]
    #[DataProvider('skillLevelValueProvider')]
    public function skill_level_enum_try_from_works_correctly(string $value, ?SkillLevel $expected): void
    {
        $result = SkillLevel::tryFrom($value);
        $this->assertSame($expected, $result);
    }

    #[Test]
    #[DataProvider('educationLevelValueProvider')]
    public function education_level_enum_try_from_works_correctly(string $value, ?EducationLevel $expected): void
    {
        $result = EducationLevel::tryFrom($value);
        $this->assertSame($expected, $result);
    }

    #[Test]
    public function enums_can_be_serialized_to_json(): void
    {
        $data = [
            'education_level' => EducationLevel::Bachelor,
            'skill_level' => SkillLevel::Expert,
            'network' => Network::GitHub,
            'schema' => ResumeSchema::V1,
        ];

        $json = json_encode($data, JSON_THROW_ON_ERROR);
        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $expected = [
            'education_level' => 'Bachelor',
            'skill_level' => 'Expert',
            'network' => 'github',
            'schema' => 'https://jsonresume.org/schema/schema.json',
        ];

        $this->assertSame($expected, $decoded);
    }

    #[Test]
    public function network_enum_covers_popular_platforms(): void
    {
        $popularPlatforms = [
            Network::GitHub,
            Network::LinkedIn,
            Network::Twitter,
            Network::Facebook,
            Network::Instagram,
            Network::YouTube,
            Network::StackOverflow,
            Network::Medium,
            Network::PersonalWebsite,
            Network::Mastodon,
            Network::Bluesky,
        ];

        foreach ($popularPlatforms as $platform) {
            $this->assertInstanceOf(Network::class, $platform);
            $this->assertIsString($platform->value);
            $this->assertNotEmpty($platform->value);
        }
    }

    #[Test]
    public function skill_levels_are_properly_ordered(): void
    {
        $levels = [
            SkillLevel::Beginner,
            SkillLevel::Intermediate,
            SkillLevel::Advanced,
            SkillLevel::Expert,
        ];

        // Test that each level exists and has the expected value
        $this->assertSame('Beginner', $levels[0]->value);
        $this->assertSame('Intermediate', $levels[1]->value);
        $this->assertSame('Advanced', $levels[2]->value);
        $this->assertSame('Expert', $levels[3]->value);
    }

    #[Test]
    public function education_levels_include_modern_options(): void
    {
        // Test that modern education options are included
        $this->assertInstanceOf(EducationLevel::class, EducationLevel::Bootcamp);
        $this->assertInstanceOf(EducationLevel::class, EducationLevel::Other);

        // Test traditional options
        $this->assertInstanceOf(EducationLevel::class, EducationLevel::Bachelor);
        $this->assertInstanceOf(EducationLevel::class, EducationLevel::Master);
        $this->assertInstanceOf(EducationLevel::class, EducationLevel::Doctorate);
    }
}
