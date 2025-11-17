<?php

declare(strict_types=1);

namespace Tests\Builders;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Certificate;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Interest;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Publication;
use JustSteveKing\Resume\DataObjects\Reference;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Volunteer;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\SkillLevel;
use LogicException;
use Tests\PackageTestCase;

final class ResumeBuilderTest extends PackageTestCase
{
    private ResumeBuilder $builder;
    private Basics $basics;

    protected function setUp(): void
    {
        $this->builder = new ResumeBuilder();
        $this->basics = new Basics(
            name: 'John Doe',
            label: 'Software Engineer',
            email: 'john@example.com',
        );
    }

    public function test_can_build_resume_with_only_basics(): void
    {
        $resume = $this->builder
            ->basics($this->basics)
            ->build();

        $this->assertSame($this->basics, $resume->basics);
        $this->assertSame([], $resume->work);
        $this->assertSame([], $resume->volunteer);
        $this->assertSame([], $resume->education);
        $this->assertSame([], $resume->awards);
        $this->assertSame([], $resume->certificates);
        $this->assertSame([], $resume->publications);
        $this->assertSame([], $resume->skills);
        $this->assertSame([], $resume->languages);
        $this->assertSame([], $resume->interests);
        $this->assertSame([], $resume->references);
        $this->assertSame([], $resume->projects);
    }

    public function test_throws_exception_when_building_without_basics(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Basics section is required');

        $this->builder->build();
    }

    public function test_can_add_work_experience(): void
    {
        $work1 = new Work(
            name: 'Tech Corp',
            location: 'San Francisco',
            position: 'Senior Developer',
            startDate: '2020-01-01',
            endDate: '2023-12-31',
        );

        $work2 = new Work(
            name: 'Startup Inc',
            location: 'Remote',
            position: 'Developer',
            startDate: '2018-01-01',
            endDate: '2019-12-31',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addWork($work1)
            ->addWork($work2)
            ->build();

        $this->assertCount(2, $resume->work);
        $this->assertSame($work1, $resume->work[0]);
        $this->assertSame($work2, $resume->work[1]);
    }

    public function test_can_add_education(): void
    {
        $education = new Education(
            institution: 'University of Technology',
            area: 'Computer Science',
            studyType: EducationLevel::Bachelor,
            startDate: '2014-09-01',
            endDate: '2018-06-01',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addEducation($education)
            ->build();

        $this->assertCount(1, $resume->education);
        $this->assertSame($education, $resume->education[0]);
    }

    public function test_can_add_skills(): void
    {
        $skill1 = new Skill(
            name: 'PHP',
            level: SkillLevel::Expert,
            keywords: ['Laravel', 'Symfony', 'API Development'],
        );

        $skill2 = new Skill(
            name: 'JavaScript',
            level: SkillLevel::Advanced,
            keywords: ['React', 'Node.js', 'TypeScript'],
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addSkill($skill1)
            ->addSkill($skill2)
            ->build();

        $this->assertCount(2, $resume->skills);
        $this->assertSame($skill1, $resume->skills[0]);
        $this->assertSame($skill2, $resume->skills[1]);
    }

    public function test_can_add_volunteer_experience(): void
    {
        $volunteer = new Volunteer(
            organization: 'Local Food Bank',
            position: 'Web Developer',
            startDate: '2019-01-01',
            endDate: '2020-01-01',
            summary: 'Developed website for volunteer coordination',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addVolunteer($volunteer)
            ->build();

        $this->assertCount(1, $resume->volunteer);
        $this->assertSame($volunteer, $resume->volunteer[0]);
    }

    public function test_can_add_awards(): void
    {
        $award = new Award(
            title: 'Employee of the Year',
            date: '2022-12-01',
            awarder: 'Tech Corp',
            summary: 'Recognized for outstanding performance',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addAward($award)
            ->build();

        $this->assertCount(1, $resume->awards);
        $this->assertSame($award, $resume->awards[0]);
    }

    public function test_can_add_certificates(): void
    {
        $certificate = new Certificate(
            name: 'AWS Certified Solutions Architect',
            date: '2023-01-15',
            issuer: 'Amazon Web Services',
            url: 'https://aws.amazon.com/certification/',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addCertificate($certificate)
            ->build();

        $this->assertCount(1, $resume->certificates);
        $this->assertSame($certificate, $resume->certificates[0]);
    }

    public function test_can_add_publications(): void
    {
        $publication = new Publication(
            name: 'Modern PHP Development Patterns',
            publisher: 'Tech Journal',
            releaseDate: '2023-03-01',
            url: 'https://techjournal.com/modern-php',
            summary: 'An article about modern PHP development practices',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addPublication($publication)
            ->build();

        $this->assertCount(1, $resume->publications);
        $this->assertSame($publication, $resume->publications[0]);
    }

    public function test_can_add_languages(): void
    {
        $language1 = new Language(
            language: 'English',
            fluency: 'Native',
        );

        $language2 = new Language(
            language: 'Spanish',
            fluency: 'Conversational',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addLanguage($language1)
            ->addLanguage($language2)
            ->build();

        $this->assertCount(2, $resume->languages);
        $this->assertSame($language1, $resume->languages[0]);
        $this->assertSame($language2, $resume->languages[1]);
    }

    public function test_can_add_interests(): void
    {
        $interest = new Interest(
            name: 'Technology',
            keywords: ['Open Source', 'Machine Learning', 'Web Development'],
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addInterest($interest)
            ->build();

        $this->assertCount(1, $resume->interests);
        $this->assertSame($interest, $resume->interests[0]);
    }

    public function test_can_add_references(): void
    {
        $reference = new Reference(
            name: 'Jane Smith',
            reference: 'John is an excellent developer with strong problem-solving skills.',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addReference($reference)
            ->build();

        $this->assertCount(1, $resume->references);
        $this->assertSame($reference, $resume->references[0]);
    }

    public function test_can_add_projects(): void
    {
        $project = new Project(
            name: 'E-commerce Platform',
            startDate: '2023-01-01',
            endDate: '2023-06-01',
            description: 'Built a full-stack e-commerce platform using Laravel and React',
            highlights: ['Handled 10k+ users', 'Implemented payment gateway', 'Mobile responsive'],
            url: 'https://github.com/johndoe/ecommerce',
        );

        $resume = $this->builder
            ->basics($this->basics)
            ->addProject($project)
            ->build();

        $this->assertCount(1, $resume->projects);
        $this->assertSame($project, $resume->projects[0]);
    }

    public function test_builder_methods_return_self_for_chaining(): void
    {
        $work = new Work(name: 'Tech Corp', position: 'Developer');
        $skill = new Skill(name: 'PHP');

        $result = $this->builder
            ->basics($this->basics)
            ->addWork($work)
            ->addSkill($skill);

        $this->assertSame($this->builder, $result);
    }

    public function test_can_build_complete_resume(): void
    {
        $work = new Work(name: 'Tech Corp', position: 'Senior Developer');
        $education = new Education(institution: 'University', studyType: EducationLevel::Bachelor);
        $skill = new Skill(name: 'PHP', level: SkillLevel::Expert);
        $project = new Project(name: 'Cool Project');

        $resume = $this->builder
            ->basics($this->basics)
            ->addWork($work)
            ->addEducation($education)
            ->addSkill($skill)
            ->addProject($project)
            ->build();

        $this->assertInstanceOf(Resume::class, $resume);
        $this->assertSame($this->basics, $resume->basics);
        $this->assertCount(1, $resume->work);
        $this->assertCount(1, $resume->education);
        $this->assertCount(1, $resume->skills);
        $this->assertCount(1, $resume->projects);
        $this->assertCount(0, $resume->volunteer);
        $this->assertCount(0, $resume->awards);
    }
}
