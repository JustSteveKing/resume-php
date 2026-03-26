<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\DataObjects;

use JsonSerializable;
use JustSteveKing\Resume\Attributes\Field;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Url;

final readonly class Profile implements JsonSerializable
{
    /**
     * @param Network $network
     * @param string $username
     * @param Url|null $url
     */
    public function __construct(
        #[Field('network')]
        public Network $network,
        #[Field('username')]
        public string $username,
        #[Field('url')]
        public ?Url $url = null,
    ) {}

    /**
     * Convert the Profile instance to an array for JSON serialization.
     *
     * @return array{
     *     network: string,
     *     username: string,
     *     url?: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'network' => $this->network->value,
            'username' => $this->username,
            'url' => $this->url?->jsonSerialize(),
        ];
    }
}
