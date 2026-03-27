<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exporters;

use JustSteveKing\Resume\DataObjects\Resume;
use Symfony\Component\Yaml\Yaml;

final class YamlExporter implements Exporter
{
    /**
     * Export the résumé to YAML format.
     *
     * @param Resume $resume
     * @param int $inline The level where you switch to inline YAML.
     * @param int $indent The amount of spaces to use for indentation.
     * @param int<0, 64721> $flags A bitmask of Symfony\Component\Yaml\Yaml::DUMP_* flags.
     * @return string
     */
    public function export(
        Resume $resume,
        int $inline = 10,
        int $indent = 2,
        int $flags = 0,
    ): string {
        return Yaml::dump(
            input: $resume->jsonSerialize(),
            inline: $inline,
            indent: $indent,
            flags: $flags,
        );
    }
}
