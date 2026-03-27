<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Builders;

use JustSteveKing\Resume\DataObjects\Location;

final class LocationBuilder
{
    private ?string $address = null;
    private ?string $postalCode = null;
    private ?string $city = null;
    private ?string $countryCode = null;
    private ?string $region = null;

    public function __construct(private readonly BasicsBuilder $parent) {}

    public function address(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function postalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function city(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function countryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function region(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function end(): BasicsBuilder
    {
        return $this->parent;
    }

    public function build(): Location
    {
        return new Location(
            address: $this->address,
            postalCode: $this->postalCode,
            city: $this->city,
            countryCode: $this->countryCode,
            region: $this->region,
        );
    }
}
