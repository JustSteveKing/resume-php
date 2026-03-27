<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Factories;

use DateTimeImmutable;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Certificate;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Interest;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Publication;
use JustSteveKing\Resume\DataObjects\Reference;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Volunteer;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\ResumeSchema;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\Exceptions\HydrationException;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use Symfony\Component\Yaml\Yaml;
use Throwable;

final class ResumeFactory
{
    public static function fromYaml(string $yaml): Resume
    {
        try {
            /** @var array<string, mixed> $data */
            $data = Yaml::parse($yaml);
            return self::fromArray($data);
        } catch (Throwable $e) {
            throw new HydrationException("Invalid YAML provided: {$e->getMessage()}", $e);
        }
    }

    public static function fromJson(string $json): Resume
    {
        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            return self::fromArray($data);
        } catch (Throwable $e) {
            throw new HydrationException("Invalid JSON provided: {$e->getMessage()}", $e);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return Resume
     */
    public static function fromArray(array $data): Resume
    {
        try {
            if ( ! isset($data['basics']) || ! is_array($data['basics'])) {
                throw new HydrationException('Missing basics section');
            }

            /** @var array<string, mixed> $basicsData */
            $basicsData = $data['basics'];
            $basics = self::hydrateBasics($basicsData);

            /** @var array<array<string, mixed>> $work */
            $work = (isset($data['work']) && is_array($data['work'])) ? $data['work'] : [];
            /** @var array<array<string, mixed>> $volunteer */
            $volunteer = (isset($data['volunteer']) && is_array($data['volunteer'])) ? $data['volunteer'] : [];
            /** @var array<array<string, mixed>> $education */
            $education = (isset($data['education']) && is_array($data['education'])) ? $data['education'] : [];
            /** @var array<array<string, mixed>> $awards */
            $awards = (isset($data['awards']) && is_array($data['awards'])) ? $data['awards'] : [];
            /** @var array<array<string, mixed>> $certificates */
            $certificates = (isset($data['certificates']) && is_array($data['certificates'])) ? $data['certificates'] : [];
            /** @var array<array<string, mixed>> $publications */
            $publications = (isset($data['publications']) && is_array($data['publications'])) ? $data['publications'] : [];
            /** @var array<array<string, mixed>> $skills */
            $skills = (isset($data['skills']) && is_array($data['skills'])) ? $data['skills'] : [];
            /** @var array<array<string, mixed>> $languages */
            $languages = (isset($data['languages']) && is_array($data['languages'])) ? $data['languages'] : [];
            /** @var array<array<string, mixed>> $interests */
            $interests = (isset($data['interests']) && is_array($data['interests'])) ? $data['interests'] : [];
            /** @var array<array<string, mixed>> $references */
            $references = (isset($data['references']) && is_array($data['references'])) ? $data['references'] : [];
            /** @var array<array<string, mixed>> $projects */
            $projects = (isset($data['projects']) && is_array($data['projects'])) ? $data['projects'] : [];

            $schemaValue = (isset($data['$schema']) && is_string($data['$schema'])) ? $data['$schema'] : '';

            return new Resume(
                basics: $basics,
                work: array_values(array_map(fn(array $item): Work => self::hydrateWork($item), $work)),
                volunteer: array_values(array_map(fn(array $item): Volunteer => self::hydrateVolunteer($item), $volunteer)),
                education: array_values(array_map(fn(array $item): Education => self::hydrateEducation($item), $education)),
                awards: array_values(array_map(fn(array $item): Award => self::hydrateAward($item), $awards)),
                certificates: array_values(array_map(fn(array $item): Certificate => self::hydrateCertificate($item), $certificates)),
                publications: array_values(array_map(fn(array $item): Publication => self::hydratePublication($item), $publications)),
                skills: array_values(array_map(fn(array $item): Skill => self::hydrateSkill($item), $skills)),
                languages: array_values(array_map(fn(array $item): Language => self::hydrateLanguage($item), $languages)),
                interests: array_values(array_map(fn(array $item): Interest => self::hydrateInterest($item), $interests)),
                references: array_values(array_map(fn(array $item): Reference => self::hydrateReference($item), $references)),
                projects: array_values(array_map(fn(array $item): Project => self::hydrateProject($item), $projects)),
                schema: ResumeSchema::tryFrom($schemaValue) ?? ResumeSchema::V1,
            );
        } catch (Throwable $e) {
            if ($e instanceof HydrationException) {
                throw $e;
            }
            throw new HydrationException("Failed to hydrate Resume: {$e->getMessage()}", $e);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return Basics
     */
    private static function hydrateBasics(array $data): Basics
    {
        /** @var array<array<string, mixed>> $profiles */
        $profiles = (isset($data['profiles']) && is_array($data['profiles'])) ? $data['profiles'] : [];

        /** @var array<string, mixed>|null $locationData */
        $locationData = (isset($data['location']) && is_array($data['location'])) ? $data['location'] : null;
        $location = (null !== $locationData) ? self::hydrateLocation($locationData) : null;

        return new Basics(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            label: is_string($data['label'] ?? null) ? (string) $data['label'] : '',
            image: is_string($data['image'] ?? null) ? new Url((string) $data['image']) : null,
            email: is_string($data['email'] ?? null) ? new Email((string) $data['email']) : null,
            phone: is_string($data['phone'] ?? null) ? (string) $data['phone'] : null,
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
            summary: is_string($data['summary'] ?? null) ? (string) $data['summary'] : null,
            location: $location,
            profiles: array_values(array_map(fn(array $item): Profile => self::hydrateProfile($item), $profiles)),
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Location
     */
    private static function hydrateLocation(array $data): Location
    {
        return new Location(
            address: is_string($data['address'] ?? null) ? (string) $data['address'] : null,
            postalCode: is_string($data['postalCode'] ?? null) ? (string) $data['postalCode'] : null,
            city: is_string($data['city'] ?? null) ? (string) $data['city'] : null,
            countryCode: is_string($data['countryCode'] ?? null) ? (string) $data['countryCode'] : null,
            region: is_string($data['region'] ?? null) ? (string) $data['region'] : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Profile
     */
    private static function hydrateProfile(array $data): Profile
    {
        $networkValue = is_string($data['network'] ?? null) ? (string) $data['network'] : '';

        return new Profile(
            network: Network::tryFrom($networkValue) ?? Network::Other,
            username: is_string($data['username'] ?? null) ? (string) $data['username'] : '',
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Work
     */
    private static function hydrateWork(array $data): Work
    {
        /** @var list<string> $highlights */
        $highlights = (isset($data['highlights']) && is_array($data['highlights'])) ? array_values(array_filter($data['highlights'], 'is_string')) : [];

        return new Work(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            position: is_string($data['position'] ?? null) ? (string) $data['position'] : '',
            location: is_string($data['location'] ?? null) ? (string) $data['location'] : null,
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
            startDate: is_string($data['startDate'] ?? null) ? new DateTimeImmutable((string) $data['startDate']) : null,
            endDate: is_string($data['endDate'] ?? null) ? new DateTimeImmutable((string) $data['endDate']) : null,
            summary: is_string($data['summary'] ?? null) ? (string) $data['summary'] : null,
            highlights: $highlights,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Volunteer
     */
    private static function hydrateVolunteer(array $data): Volunteer
    {
        /** @var list<string> $highlights */
        $highlights = (isset($data['highlights']) && is_array($data['highlights'])) ? array_values(array_filter($data['highlights'], 'is_string')) : [];

        return new Volunteer(
            organization: is_string($data['organization'] ?? null) ? (string) $data['organization'] : '',
            position: is_string($data['position'] ?? null) ? (string) $data['position'] : '',
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
            startDate: is_string($data['startDate'] ?? null) ? new DateTimeImmutable((string) $data['startDate']) : null,
            endDate: is_string($data['endDate'] ?? null) ? new DateTimeImmutable((string) $data['endDate']) : null,
            summary: is_string($data['summary'] ?? null) ? (string) $data['summary'] : null,
            highlights: $highlights,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Education
     */
    private static function hydrateEducation(array $data): Education
    {
        $studyTypeValue = is_string($data['studyType'] ?? null) ? (string) $data['studyType'] : '';
        /** @var list<string> $courses */
        $courses = (isset($data['courses']) && is_array($data['courses'])) ? array_values(array_filter($data['courses'], 'is_string')) : [];

        return new Education(
            institution: is_string($data['institution'] ?? null) ? (string) $data['institution'] : '',
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
            area: is_string($data['area'] ?? null) ? (string) $data['area'] : null,
            studyType: EducationLevel::tryFrom($studyTypeValue),
            startDate: is_string($data['startDate'] ?? null) ? new DateTimeImmutable((string) $data['startDate']) : null,
            endDate: is_string($data['endDate'] ?? null) ? new DateTimeImmutable((string) $data['endDate']) : null,
            score: is_string($data['score'] ?? null) ? (string) $data['score'] : null,
            courses: $courses,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Award
     */
    private static function hydrateAward(array $data): Award
    {
        return new Award(
            title: is_string($data['title'] ?? null) ? (string) $data['title'] : '',
            date: new DateTimeImmutable(is_string($data['date'] ?? null) ? (string) $data['date'] : 'now'),
            awarder: is_string($data['awarder'] ?? null) ? (string) $data['awarder'] : '',
            summary: is_string($data['summary'] ?? null) ? (string) $data['summary'] : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Certificate
     */
    private static function hydrateCertificate(array $data): Certificate
    {
        return new Certificate(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            date: new DateTimeImmutable(is_string($data['date'] ?? null) ? (string) $data['date'] : 'now'),
            issuer: is_string($data['issuer'] ?? null) ? (string) $data['issuer'] : '',
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Publication
     */
    private static function hydratePublication(array $data): Publication
    {
        return new Publication(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            publisher: is_string($data['publisher'] ?? null) ? (string) $data['publisher'] : '',
            releaseDate: new DateTimeImmutable(is_string($data['releaseDate'] ?? null) ? (string) $data['releaseDate'] : 'now'),
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
            summary: is_string($data['summary'] ?? null) ? (string) $data['summary'] : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Skill
     */
    private static function hydrateSkill(array $data): Skill
    {
        $levelValue = is_string($data['level'] ?? null) ? (string) $data['level'] : '';
        /** @var list<string> $keywords */
        $keywords = (isset($data['keywords']) && is_array($data['keywords'])) ? array_values(array_filter($data['keywords'], 'is_string')) : [];

        return new Skill(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            level: SkillLevel::tryFrom($levelValue),
            keywords: $keywords,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Language
     */
    private static function hydrateLanguage(array $data): Language
    {
        return new Language(
            language: is_string($data['language'] ?? null) ? (string) $data['language'] : '',
            fluency: is_string($data['fluency'] ?? null) ? (string) $data['fluency'] : null,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Interest
     */
    private static function hydrateInterest(array $data): Interest
    {
        /** @var list<string> $keywords */
        $keywords = (isset($data['keywords']) && is_array($data['keywords'])) ? array_values(array_filter($data['keywords'], 'is_string')) : [];

        return new Interest(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            keywords: $keywords,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Reference
     */
    private static function hydrateReference(array $data): Reference
    {
        return new Reference(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            reference: is_string($data['reference'] ?? null) ? (string) $data['reference'] : '',
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return Project
     */
    private static function hydrateProject(array $data): Project
    {
        /** @var list<string> $highlights */
        $highlights = (isset($data['highlights']) && is_array($data['highlights'])) ? array_values(array_filter($data['highlights'], 'is_string')) : [];

        return new Project(
            name: is_string($data['name'] ?? null) ? (string) $data['name'] : '',
            startDate: is_string($data['startDate'] ?? null) ? new DateTimeImmutable((string) $data['startDate']) : null,
            endDate: is_string($data['endDate'] ?? null) ? new DateTimeImmutable((string) $data['endDate']) : null,
            description: is_string($data['description'] ?? null) ? (string) $data['description'] : null,
            highlights: $highlights,
            url: is_string($data['url'] ?? null) ? new Url((string) $data['url']) : null,
        );
    }
}
