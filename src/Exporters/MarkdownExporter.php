<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exporters;

use JustSteveKing\Resume\DataObjects\Resume;

final class MarkdownExporter implements Exporter
{
    /**
     * @param array{
     *     basics: bool,
     *     contact: bool,
     *     profiles: bool,
     *     work: bool,
     *     education: bool,
     *     skills: bool,
     *     languages: bool
     * } $options
     */
    public function __construct(
        private array $options = [
            'basics' => true,
            'contact' => true,
            'profiles' => true,
            'work' => true,
            'education' => true,
            'skills' => true,
            'languages' => true,
        ]
    ) {}

    /**
     * Export the résumé to Markdown format.
     *
     * @param Resume $resume
     * @return string
     */
    public function export(Resume $resume): string
    {
        $options = array_replace_recursive([
            'basics' => true,
            'contact' => true,
            'profiles' => true,
            'work' => true,
            'education' => true,
            'skills' => true,
            'languages' => true,
        ], $this->options);

        $md = [];

        // Basics
        if ($options['basics']) {
            $md[] = "# {$resume->basics->name}";
            $md[] = "**{$resume->basics->label}**";
            if ( ! empty($resume->basics->summary)) {
                $md[] = $resume->basics->summary;
            }
            $md[] = '';
        }

        // Contact Info
        if ($options['contact']) {
            $md[] = "📧 Email: [{$resume->basics->email}](mailto:{$resume->basics->email})";
            $md[] = "🌍 Website: [{$resume->basics->url}]({$resume->basics->url})";
            if ( ! empty($resume->basics->location)) {
                $location = "{$resume->basics->location->city}, {$resume->basics->location->countryCode}";
                $md[] = "📍 Location: {$location}";
            }
        }

        // Profiles
        if ($options['profiles'] && ! empty($resume->basics->profiles)) {
            $md[] = "\n### 🔗 Social Profiles";
            foreach ($resume->basics->profiles as $profile) {
                $md[] = "- [{$profile->network->value}]({$profile->url})";
            }
        }

        // Work Experience
        if ($options['work'] && ! empty($resume->work)) {
            $md[] = "\n## 💼 Work Experience";
            foreach ($resume->work as $job) {
                $md[] = "### {$job->position} at {$job->name}";
                $md[] = "_{$job->startDate} → {$job->endDate}_";
                if ( ! empty($job->summary)) {
                    $md[] = $job->summary;
                }
                foreach ($job->highlights as $highlight) {
                    $md[] = "- {$highlight}";
                }
                $md[] = '';
            }
        }

        // Education
        if ($options['education'] && ! empty($resume->education)) {
            $md[] = "\n## 🎓 Education";
            foreach ($resume->education as $edu) {
                $md[] = "### {$edu->institution}";
                $md[] = "_{$edu->startDate} → {$edu->endDate}_";
                $md[] = "{$edu->area} in {$edu->studyType?->value}";
                $md[] = '';
            }
        }

        // Skills
        if ($options['skills'] && ! empty($resume->skills)) {
            $md[] = "\n## 🛠 Skills";
            foreach ($resume->skills as $skill) {
                $md[] = "- **{$skill->name}**: " . implode(', ', $skill->keywords);
            }
        }

        // Languages
        if ($options['languages'] && ! empty($resume->languages)) {
            $md[] = "\n## 🌍 Languages";
            foreach ($resume->languages as $lang) {
                $md[] = "- {$lang->language} ({$lang->fluency})";
            }
        }

        return implode("\n", array_filter($md));
    }
}
