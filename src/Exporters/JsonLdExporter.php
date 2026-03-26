<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exporters;

use JustSteveKing\Resume\DataObjects\Resume;

final class JsonLdExporter implements Exporter
{
    /**
     * Export the résumé to JSON-LD format.
     *
     * @param Resume $resume
     * @return array<string, mixed>
     */
    public function export(Resume $resume): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $resume->basics->name,
            'url' => $resume->basics->url?->value,
            'jobTitle' => $resume->basics->label,
            'sameAs' => array_filter(array_map(
                static fn($profile): ?string => $profile->url?->value,
                $resume->basics->profiles,
            )),
            'knowsAbout' => array_map(
                static fn($skill): string => $skill->name,
                $resume->skills,
            ),
        ];
    }
}
