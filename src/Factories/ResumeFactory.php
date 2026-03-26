<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Factories;

use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Certificate;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Interest;
use JustSteveKing\Resume\DataObjects\JobDescription;
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
use Throwable;

final class ResumeFactory
{
    public static function fromJson(string $json): Resume
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new HydrationException('Invalid JSON provided: ' . $e->getMessage(), $e);
        }

        return self::fromArray($data);
    }

    public static function fromArray(array $data): Resume
    {
        try {
            return new Resume(
                basics: self::hydrateBasics($data['basics'] ?? throw new HydrationException('Missing basics section')),
                work: array_map(fn (array $item) => self::hydrateWork($item), $data['work'] ?? []),
                volunteer: array_map(fn (array $item) => self::hydrateVolunteer($item), $data['volunteer'] ?? []),
                education: array_map(fn (array $item) => self::hydrateEducation($item), $data['education'] ?? []),
                awards: array_map(fn (array $item) => self::hydrateAward($item), $data['awards'] ?? []),
                certificates: array_map(fn (array $item) => self::hydrateCertificate($item), $data['certificates'] ?? []),
                publications: array_map(fn (array $item) => self::hydratePublication($item), $data['publications'] ?? []),
                skills: array_map(fn (array $item) => self::hydrateSkill($item), $data['skills'] ?? []),
                languages: array_map(fn (array $item) => self::hydrateLanguage($item), $data['languages'] ?? []),
                interests: array_map(fn (array $item) => self::hydrateInterest($item), $data['interests'] ?? []),
                references: array_map(fn (array $item) => self::hydrateReference($item), $data['references'] ?? []),
                projects: array_map(fn (array $item) => self::hydrateProject($item), $data['projects'] ?? []),
                schema: ResumeSchema::tryFrom($data['$schema'] ?? '') ?? ResumeSchema::V1,
            );
        } catch (HydrationException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new HydrationException('Failed to hydrate Resume: ' . $e->getMessage(), $e);
        }
    }

    private static function hydrateBasics(array $data): Basics
    {
        return new Basics(
            name: $data['name'] ?? throw new HydrationException('Missing name in basics'),
            label: $data['label'] ?? throw new HydrationException('Missing label in basics'),
            image: $data['image'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            url: $data['url'] ?? null,
            summary: $data['summary'] ?? null,
            location: isset($data['location']) ? self::hydrateLocation($data['location']) : null,
            profiles: array_map(fn (array $item) => self::hydrateProfile($item), $data['profiles'] ?? []),
        );
    }

    private static function hydrateLocation(array $data): Location
    {
        return new Location(
            address: $data['address'] ?? null,
            postalCode: $data['postalCode'] ?? null,
            city: $data['city'] ?? null,
            countryCode: $data['countryCode'] ?? null,
            region: $data['region'] ?? null,
        );
    }

    private static function hydrateProfile(array $data): Profile
    {
        $networkValue = $data['network'] ?? throw new HydrationException('Missing network in profile');
        $network = Network::tryFrom($networkValue);
        
        if (!$network) {
            // Fallback to Other if not found, or maybe we should add it?
            // Given the instruction, if it's invalid we should throw HydrationException.
            // But if example.resume.json has "Starfleet Database", we might want to handle it.
            // Let's try to be strict first.
            throw new HydrationException("Invalid network: {$networkValue}");
        }

        return new Profile(
            network: $network,
            username: $data['username'] ?? throw new HydrationException('Missing username in profile'),
            url: $data['url'] ?? null,
        );
    }

    private static function hydrateWork(array $data): Work
    {
        return new Work(
            name: $data['name'] ?? throw new HydrationException('Missing name in work'),
            position: $data['position'] ?? throw new HydrationException('Missing position in work'),
            location: $data['location'] ?? null,
            url: $data['url'] ?? null,
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
            summary: $data['summary'] ?? null,
            highlights: $data['highlights'] ?? [],
        );
    }

    private static function hydrateVolunteer(array $data): Volunteer
    {
        return new Volunteer(
            organization: $data['organization'] ?? throw new HydrationException('Missing organization in volunteer'),
            position: $data['position'] ?? throw new HydrationException('Missing position in volunteer'),
            url: $data['url'] ?? null,
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
            summary: $data['summary'] ?? null,
            highlights: $data['highlights'] ?? [],
        );
    }

    private static function hydrateEducation(array $data): Education
    {
        return new Education(
            institution: $data['institution'] ?? throw new HydrationException('Missing institution in education'),
            url: $data['url'] ?? null,
            area: $data['area'] ?? null,
            studyType: isset($data['studyType']) ? EducationLevel::tryFrom($data['studyType']) : null,
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
            score: $data['score'] ?? null,
            courses: $data['courses'] ?? [],
        );
    }

    private static function hydrateAward(array $data): Award
    {
        return new Award(
            title: $data['title'] ?? throw new HydrationException('Missing title in award'),
            date: $data['date'] ?? null,
            awarder: $data['awarder'] ?? null,
            summary: $data['summary'] ?? null,
        );
    }

    private static function hydrateCertificate(array $data): Certificate
    {
        return new Certificate(
            name: $data['name'] ?? throw new HydrationException('Missing name in certificate'),
            date: $data['date'] ?? null,
            issuer: $data['issuer'] ?? null,
            url: $data['url'] ?? null,
        );
    }

    private static function hydratePublication(array $data): Publication
    {
        return new Publication(
            name: $data['name'] ?? throw new HydrationException('Missing name in publication'),
            publisher: $data['publisher'] ?? null,
            releaseDate: $data['releaseDate'] ?? null,
            url: $data['url'] ?? null,
            summary: $data['summary'] ?? null,
        );
    }

    private static function hydrateSkill(array $data): Skill
    {
        return new Skill(
            name: $data['name'] ?? throw new HydrationException('Missing name in skill'),
            level: isset($data['level']) ? SkillLevel::tryFrom($data['level']) : null,
            keywords: $data['keywords'] ?? [],
        );
    }

    private static function hydrateLanguage(array $data): Language
    {
        return new Language(
            language: $data['language'] ?? throw new HydrationException('Missing language in languages'),
            fluency: $data['fluency'] ?? null,
        );
    }

    private static function hydrateInterest(array $data): Interest
    {
        return new Interest(
            name: $data['name'] ?? throw new HydrationException('Missing name in interest'),
            keywords: $data['keywords'] ?? [],
        );
    }

    private static function hydrateReference(array $data): Reference
    {
        return new Reference(
            name: $data['name'] ?? throw new HydrationException('Missing name in reference'),
            reference: $data['reference'] ?? throw new HydrationException('Missing reference content'),
        );
    }

    private static function hydrateProject(array $data): Project
    {
        return new Project(
            name: $data['name'] ?? throw new HydrationException('Missing name in project'),
            description: $data['description'] ?? null,
            highlights: $data['highlights'] ?? [],
            keywords: $data['keywords'] ?? [],
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
            url: $data['url'] ?? null,
            roles: $data['roles'] ?? [],
            entity: $data['entity'] ?? null,
            type: $data['type'] ?? null,
        );
    }
}
