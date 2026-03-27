<?php

declare(strict_types=1);

namespace Tests\DataObjects;

use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\PackageTestCase;

final class ProfileTest extends PackageTestCase
{
    public static function networkProvider(): array
    {
        return array_map(
            static fn(Network $network) => [$network],
            Network::cases(),
        );
    }

    public static function socialNetworkProfileProvider(): array
    {
        return [
            'GitHub profile' => [
                Network::GitHub,
                'johndoe',
                'https://github.com/johndoe',
            ],
            'LinkedIn profile' => [
                Network::LinkedIn,
                'johndoe',
                'https://linkedin.com/in/johndoe',
            ],
            'Twitter profile' => [
                Network::Twitter,
                'johndoe',
                'https://twitter.com/johndoe',
            ],
            'StackOverflow profile' => [
                Network::StackOverflow,
                'johndoe',
                'https://stackoverflow.com/users/123456/johndoe',
            ],
            'Personal website' => [
                Network::PersonalWebsite,
                'johndoe.com',
                'https://johndoe.com',
            ],
            'Profile without URL' => [
                Network::Discord,
                'johndoe#1234',
                null,
            ],
            'Mastodon profile' => [
                Network::Mastodon,
                '@johndoe@mastodon.social',
                'https://mastodon.social/@johndoe',
            ],
            'Bluesky profile' => [
                Network::Bluesky,
                'johndoe.bsky.social',
                'https://bsky.app/profile/johndoe.bsky.social',
            ],
        ];
    }
    #[Test]
    public function can_create_profile_with_required_fields(): void
    {
        $profile = new Profile(
            network: Network::GitHub,
            username: 'johndoe',
        );

        $this->assertSame(Network::GitHub, $profile->network);
        $this->assertSame('johndoe', $profile->username);
        $this->assertNull($profile->url);
    }

    #[Test]
    public function can_create_profile_with_all_fields(): void
    {
        $profile = new Profile(
            network: Network::LinkedIn,
            username: 'johndoe',
            url: new Url('https://linkedin.com/in/johndoe'),
        );

        $this->assertSame(Network::LinkedIn, $profile->network);
        $this->assertSame('johndoe', $profile->username);
        $this->assertSame('https://linkedin.com/in/johndoe', $profile->url->value);
    }

    #[Test]
    #[DataProvider('networkProvider')]
    public function supports_all_network_types(Network $network): void
    {
        $profile = new Profile(
            network: $network,
            username: 'testuser',
        );

        $this->assertSame($network, $profile->network);
        $this->assertSame('testuser', $profile->username);
    }

    #[Test]
    public function json_serialization_with_minimal_data(): void
    {
        $profile = new Profile(
            network: Network::GitHub,
            username: 'johndoe',
        );

        $expected = [
            'network' => 'github',
            'username' => 'johndoe',
        ];

        $this->assertSame($expected, $profile->jsonSerialize());
    }

    #[Test]
    public function json_serialization_with_all_data(): void
    {
        $profile = new Profile(
            network: Network::Twitter,
            username: 'johndoe',
            url: new Url('https://twitter.com/johndoe'),
        );

        $expected = [
            'network' => 'twitter',
            'username' => 'johndoe',
            'url' => 'https://twitter.com/johndoe',
        ];

        $this->assertSame($expected, $profile->jsonSerialize());
    }

    #[Test]
    public function json_serialization_filters_null_url(): void
    {
        $profile = new Profile(
            network: Network::Facebook,
            username: 'johndoe',
            url: null,
        );

        $this->assertSame([
            'network' => 'facebook',
            'username' => 'johndoe',
        ], $profile->jsonSerialize());
    }

    #[Test]
    #[DataProvider('socialNetworkProfileProvider')]
    public function creates_realistic_social_profiles(Network $network, string $username, ?string $url): void
    {
        $profile = new Profile(
            network: $network,
            username: $username,
            url: $url ? new Url($url) : null,
        );

        $this->assertSame($network, $profile->network);
        $this->assertSame($username, $profile->username);
        $this->assertSame($url, $profile->url?->value);

        // Test serialization
        $serialized = $profile->jsonSerialize();
        $this->assertSame($network->value, $serialized['network']);
        $this->assertSame($username, $serialized['username']);

        if (null !== $url) {
            $this->assertArrayHasKey('url', $serialized);
            $this->assertSame($url, $serialized['url']);
        } else {
            $this->assertArrayNotHasKey('url', $serialized);
        }
    }

    #[Test]
    public function can_be_used_in_arrays(): void
    {
        $profiles = [
            new Profile(Network::GitHub, 'johndoe', new Url('https://github.com/johndoe')),
            new Profile(Network::LinkedIn, 'johndoe', new Url('https://linkedin.com/in/johndoe')),
            new Profile(Network::Twitter, 'johndoe'),
        ];

        $this->assertCount(3, $profiles);
        $this->assertContainsOnlyInstancesOf(Profile::class, $profiles);

        // Test that they can be serialized as an array
        $serialized = array_map(
            static fn(Profile $profile) => $profile->jsonSerialize(),
            $profiles,
        );

        $expected = [
            [
                'network' => 'github',
                'username' => 'johndoe',
                'url' => 'https://github.com/johndoe',
            ],
            [
                'network' => 'linkedin',
                'username' => 'johndoe',
                'url' => 'https://linkedin.com/in/johndoe',
            ],
            [
                'network' => 'twitter',
                'username' => 'johndoe',
            ],
        ];

        $this->assertSame($expected, $serialized);
    }
}
