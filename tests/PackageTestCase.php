<?php

declare(strict_types=1);

namespace Tests;

use DateTimeImmutable;
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Publication;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Volunteer;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use PHPUnit\Framework\TestCase;

abstract class PackageTestCase extends TestCase
{
    protected function buildCompleteResume(): Resume
    {
        $location = new Location(
            address: '123 Main St',
            postalCode: '94105',
            city: 'San Francisco',
            countryCode: 'US',
            region: 'CA',
        );

        $profiles = [
            new Profile(Network::GitHub, 'johndoe', new Url('https://github.com/johndoe')),
            new Profile(Network::LinkedIn, 'johndoe', new Url('https://linkedin.com/in/johndoe')),
        ];

        $basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: new Email('john@example.com'),
            url: new Url('https://johndoe.com'),
            summary: 'Experienced software engineer with 5+ years in web development.',
            location: $location,
            profiles: $profiles,
        );

        return new ResumeBuilder()
            ->basics($basics)
            ->addWork(new Work(
                name: 'Tech Corp',
                position: 'Senior Developer',
                startDate: new DateTimeImmutable('2020-01-01'),
                endDate: new DateTimeImmutable('2023-12-31'),
                summary: 'Led development of core platform features',
                highlights: ['Improved performance by 40%', 'Mentored junior developers'],
            ))
            ->addWork(new Work(
                name: 'Startup Inc',
                position: 'Full Stack Developer',
                startDate: new DateTimeImmutable('2018-01-01'),
                endDate: new DateTimeImmutable('2019-12-31'),
            ))
            ->addEducation(new Education(
                institution: 'University of Technology',
                area: 'Computer Science',
                studyType: EducationLevel::Bachelor,
                startDate: new DateTimeImmutable('2014-09-01'),
                endDate: new DateTimeImmutable('2018-06-01'),
            ))
            ->addSkill(new Skill(
                name: 'PHP',
                level: SkillLevel::Expert,
                keywords: ['Laravel', 'Symfony', 'API Development'],
            ))
            ->addSkill(new Skill(
                name: 'JavaScript',
                level: SkillLevel::Advanced,
                keywords: ['React', 'Node.js', 'TypeScript'],
            ))
            ->addSkill(new Skill(
                name: 'Python',
                level: SkillLevel::Intermediate,
                keywords: ['Django', 'Data Analysis'],
            ))
            ->addProject(new Project(
                name: 'E-commerce Platform',
                startDate: new DateTimeImmutable('2023-01-01'),
                endDate: new DateTimeImmutable('2023-06-01'),
                description: 'Built a full-stack e-commerce platform',
                highlights: ['Handled 10k+ users', 'Implemented payment gateway'],
                url: new Url('https://github.com/johndoe/ecommerce'),
            ))
            ->addLanguage(new Language('English', 'Native'))
            ->addLanguage(new Language('Spanish', 'Conversational'))
            ->addVolunteer(new Volunteer(
                organization: 'Local Food Bank',
                position: 'Web Developer',
                startDate: new DateTimeImmutable('2019-01-01'),
                endDate: new DateTimeImmutable('2020-01-01'),
            ))
            ->addAward(new Award(
                title: 'Employee of the Year',
                date: new DateTimeImmutable('2022-12-01'),
                awarder: 'Tech Corp',
            ))
            ->addPublication(new Publication(
                name: 'Modern PHP Development',
                publisher: 'Tech Journal',
                releaseDate: new DateTimeImmutable('2023-03-01'),
            ))
            ->build();
    }
}
