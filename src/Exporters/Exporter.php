<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exporters;

use JustSteveKing\Resume\DataObjects\Resume;

interface Exporter
{
    /**
     * Export the résumé to a specific format.
     *
     * @param Resume $resume
     * @return mixed
     */
    public function export(Resume $resume): mixed;
}
