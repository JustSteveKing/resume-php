<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;

final readonly class Location implements JsonSerializable
{
    /**
     * Create a new Location instance.
     *
     * @param string|null $address The street address.
     * @param string|null $postalCode The postal code.
     * @param string|null $city The city name.
     * @param string|null $countryCode The country code (ISO 3166-1 alpha-2).
     * @param string|null $region The region or state name.
     */
    public function __construct(
        #[Field('address')]
        public ?string $address = null,
        #[Field('postalCode')]
        public ?string $postalCode = null,
        #[Field('city')]
        public ?string $city = null,
        #[Field('countryCode')]
        public ?string $countryCode = null,
        #[Field('region')]
        public ?string $region = null,
    ) {}

    /**
     * Convert the Location instance to an array for JSON serialization.
     *
     * @return array{
     *     address?: string,
     *     postalCode?: string,
     *     city?: string,
     *     countryCode?: string,
     *     region?: string
     * }
     */
    public function jsonSerialize(): array
    {
        $data = [];

        if (null !== $this->address) {
            $data['address'] = $this->address;
        }
        if (null !== $this->postalCode) {
            $data['postalCode'] = $this->postalCode;
        }
        if (null !== $this->city) {
            $data['city'] = $this->city;
        }
        if (null !== $this->countryCode) {
            $data['countryCode'] = $this->countryCode;
        }
        if (null !== $this->region) {
            $data['region'] = $this->region;
        }

        return $data;
    }
}
