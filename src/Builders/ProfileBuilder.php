<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Builders;

use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Url;

final class ProfileBuilder
{
    private Network $network;
    private string $username = '';
    private string|Url|null $url = null;

    public function __construct(private readonly BasicsBuilder $parent) {}

    public function network(Network $network): self
    {
        $this->network = $network;
        return $this;
    }

    public function username(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function url(string|Url|null $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function end(): BasicsBuilder
    {
        return $this->parent;
    }

    public function build(): Profile
    {
        return new Profile(
            network: $this->network,
            username: $this->username,
            url: is_string($this->url) ? new Url($this->url) : $this->url,
        );
    }
}
