<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Basics implements JsonSerializable
{
    /**
     * @param string $name
     * @param string $label
     * @param string|null $image
     * @param Email|null $email
     * @param string|null $phone
     * @param Url|null $url
     * @param string|null $summary
     * @param Location|null $location
     * @param list<Profile> $profiles
     */
    public function __construct(
        #[Field('name')]
        public string $name,
        #[Field('label')]
        public string $label,
        #[Field('image')]
        public ?string $image = null,
        #[Field('email')]
        public ?Email $email = null,
        #[Field('phone')]
        public ?string $phone = null,
        #[Field('url')]
        public ?Url $url = null,
        #[Field('summary')]
        public ?string $summary = null,
        #[Field('location')]
        public ?Location $location = null,
        #[Field('profiles')]
        public array $profiles = [],
    ) {}

    /**
     * Convert the Basics instance to an array for JSON serialization.
     *
     * @return array{
     *     name: string,
     *     label: string,
     *     image?: string|null,
     *     email?: string|null,
     *     phone?: string|null,
     *     url?: string|null,
     *     summary?: string|null,
     *     location?: array<string, mixed>|null,
     *     profiles: list<array<string, mixed>>
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
            'label' => $this->label,
        ];

        if (null !== $this->email) {
            $data['email'] = $this->email->jsonSerialize();
        }
        if (null !== $this->phone) {
            $data['phone'] = $this->phone;
        }
        if (null !== $this->url) {
            $data['url'] = $this->url->jsonSerialize();
        }
        if (null !== $this->summary) {
            $data['summary'] = $this->summary;
        }
        if (null !== $this->location) {
            $data['location'] = $this->location->jsonSerialize();
        }

        $data['profiles'] = array_map(
            static fn($profile): array => $profile->jsonSerialize(),
            $this->profiles,
        );

        return $data;
    }
}
