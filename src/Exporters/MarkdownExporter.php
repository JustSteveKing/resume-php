<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Exporters;

use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Services\Translator;

final class MarkdownExporter implements Exporter
{
    private Translator $translator;

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
     * @param string $locale
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
        ],
        string $locale = 'en',
    ) {
        $this->translator = Translator::getInstance($locale);
        $this->translator->setLocale($locale);
    }

    /**
     * Export the résumé to Markdown format.
     *
     * @param Resume $resume
     * @param array<string, bool>|null $options
     * @return string
     */
    public function export(Resume $resume, ?array $options = null): string
    {
        $options = array_replace_recursive([
            'basics' => true,
            'contact' => true,
            'profiles' => true,
            'work' => true,
            'education' => true,
            'skills' => true,
            'languages' => true,
        ], $options ?? $this->options);

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
            $emailLabel = $this->translator->trans('contact.email');
            $websiteLabel = $this->translator->trans('contact.website');
            $locationLabel = $this->translator->trans('contact.location');

            if (null !== $resume->basics->email) {
                $md[] = "📧 {$emailLabel}: [{$resume->basics->email->value}](mailto:{$resume->basics->email->value})";
            }

            if (null !== $resume->basics->url) {
                $md[] = "🌍 {$websiteLabel}: [{$resume->basics->url->value}]({$resume->basics->url->value})";
            }

            if ( ! empty($resume->basics->location)) {
                $location = "{$resume->basics->location->city}, {$resume->basics->location->countryCode}";
                $md[] = "📍 {$locationLabel}: {$location}";
            }
        }

        // Profiles
        if ($options['profiles'] && ! empty($resume->basics->profiles)) {
            $profilesLabel = $this->translator->trans('social.profiles');
            $md[] = "\n### 🔗 {$profilesLabel}";
            foreach ($resume->basics->profiles as $profile) {
                $md[] = "- [{$profile->network->value}]({$profile->url?->value})";
            }
        }

        // Work Experience
        if ($options['work'] && ! empty($resume->work)) {
            $workLabel = $this->translator->trans('sections.work');
            $presentLabel = $this->translator->trans('time.present');

            $md[] = "\n## 💼 {$workLabel}";
            foreach ($resume->work as $job) {
                $startDate = $job->startDate?->format('Y-m') ?? $presentLabel;
                $endDate = $job->endDate?->format('Y-m') ?? $presentLabel;
                $md[] = "### {$job->position} at {$job->name}";
                $md[] = "_{$startDate} → {$endDate}_";
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
            $eduLabel = $this->translator->trans('sections.education');
            $presentLabel = $this->translator->trans('time.present');

            $md[] = "\n## 🎓 {$eduLabel}";
            foreach ($resume->education as $edu) {
                $startDate = $edu->startDate?->format('Y-m') ?? $presentLabel;
                $endDate = $edu->endDate?->format('Y-m') ?? $presentLabel;
                $md[] = "### {$edu->institution}";
                $md[] = "_{$startDate} → {$endDate}_";
                $md[] = "{$edu->area} in {$edu->studyType?->value}";
                $md[] = '';
            }
        }

        // Skills
        if ($options['skills'] && ! empty($resume->skills)) {
            $skillsLabel = $this->translator->trans('sections.skills');
            $md[] = "\n## 🛠 {$skillsLabel}";
            foreach ($resume->skills as $skill) {
                $md[] = "- **{$skill->name}**: " . implode(', ', $skill->keywords);
            }
        }

        // Languages
        if ($options['languages'] && ! empty($resume->languages)) {
            $langsLabel = $this->translator->trans('sections.languages');
            $md[] = "\n## 🌍 {$langsLabel}";
            foreach ($resume->languages as $lang) {
                $md[] = "- {$lang->language} ({$lang->fluency})";
            }
        }

        return implode("\n", array_filter($md));
    }
}
