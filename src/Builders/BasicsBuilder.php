<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Builders;

use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;

final class BasicsBuilder
{
    private string $name;
    private string $label;
    private ?string $image = null;
    private ?string $email = null;
    private ?string $phone = null;
    private ?string $url = null;
    private ?string $summary = null;
    private ?LocationBuilder $locationBuilder = null;
    /** @var list<ProfileBuilder> $profileBuilders */
    private array $profileBuilders = [];

    public function __construct(private readonly ResumeBuilder $parent) {}

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function image(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function phone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function summary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function location(): LocationBuilder
    {
        $this->locationBuilder = new LocationBuilder($this);
        return $this->locationBuilder;
    }

    public function addProfile(): ProfileBuilder
    {
        $builder = new ProfileBuilder($this);
        $this->profileBuilders[] = $builder;
        return $builder;
    }

    public function end(): ResumeBuilder
    {
        return $this->parent;
    }

    public function build(): Basics
    {
        return new Basics(
            name: $this->name,
            label: $this->label,
            image: $this->image,
            email: $this->email,
            phone: $this->phone,
            url: $this->url,
            summary: $this->summary,
            location: $this->locationBuilder?->build(),
            profiles: array_map(
                static fn(ProfileBuilder $builder): Profile => $builder->build(),
                $this->profileBuilders,
            ),
        );
    }
}
