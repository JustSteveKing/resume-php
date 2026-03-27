<?php

declare(strict_types=1);

namespace Tests\Builders;

use DateTimeImmutable;
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
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Volunteer;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use Tests\PackageTestCase;

final class ResumeBuilderTest extends PackageTestCase
{
    private Basics $basics;

    protected function setUp(): void
    {
        $this->basics = new Basics(
            name: 'John Doe',
            label: 'Developer',
            email: new Email('john@example.com'),
            url: new Url('https://johndoe.com'),
        );
    }

    public function testCanBuildResumeWithOnlyBasics(): void
    {
        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->build();

        $this->assertSame($this->basics, $resume->basics);
        $this->assertEmpty($resume->work);
    }

    public function testCanAddWorkExperience(): void
    {
        $work = new Work(
            name: 'Tech Corp',
            position: 'Senior Developer',
            startDate: new DateTimeImmutable('2020-01-01'),
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addWork($work)
            ->build();

        $this->assertCount(1, $resume->work);
        $this->assertSame($work, $resume->work[0]);
    }

    public function testCanAddVolunteerExperience(): void
    {
        $volunteer = new Volunteer(
            organization: 'Charity',
            position: 'Volunteer',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addVolunteer($volunteer)
            ->build();

        $this->assertCount(1, $resume->volunteer);
        $this->assertSame($volunteer, $resume->volunteer[0]);
    }

    public function testCanAddEducation(): void
    {
        $education = new Education(
            institution: 'University',
            area: 'CS',
            studyType: EducationLevel::Bachelor,
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addEducation($education)
            ->build();

        $this->assertCount(1, $resume->education);
        $this->assertSame($education, $resume->education[0]);
    }

    public function testCanAddAwards(): void
    {
        $award = new Award(
            title: 'Top Dev',
            date: new DateTimeImmutable('2023-01-01'),
            awarder: 'Tech Corp',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addAward($award)
            ->build();

        $this->assertCount(1, $resume->awards);
        $this->assertSame($award, $resume->awards[0]);
    }

    public function testCanAddCertificates(): void
    {
        $certificate = new Certificate(
            name: 'AWS',
            date: new DateTimeImmutable('2023-01-01'),
            issuer: 'Amazon',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addCertificate($certificate)
            ->build();

        $this->assertCount(1, $resume->certificates);
        $this->assertSame($certificate, $resume->certificates[0]);
    }

    public function testCanAddPublications(): void
    {
        $publication = new Publication(
            name: 'PHP Tips',
            publisher: 'Tech Blog',
            releaseDate: new DateTimeImmutable('2023-01-01'),
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addPublication($publication)
            ->build();

        $this->assertCount(1, $resume->publications);
        $this->assertSame($publication, $resume->publications[0]);
    }

    public function testCanAddSkills(): void
    {
        $skill = new Skill(
            name: 'PHP',
            level: SkillLevel::Expert,
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addSkill($skill)
            ->build();

        $this->assertCount(1, $resume->skills);
        $this->assertSame($skill, $resume->skills[0]);
    }

    public function testCanAddLanguages(): void
    {
        $language = new Language(
            language: 'English',
            fluency: 'Native',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addLanguage($language)
            ->build();

        $this->assertCount(1, $resume->languages);
        $this->assertSame($language, $resume->languages[0]);
    }

    public function testCanAddInterests(): void
    {
        $interest = new Interest(
            name: 'Coding',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addInterest($interest)
            ->build();

        $this->assertCount(1, $resume->interests);
        $this->assertSame($interest, $resume->interests[0]);
    }

    public function testCanAddReferences(): void
    {
        $reference = new Reference(
            name: 'John Doe',
            reference: 'Highly recommended',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addReference($reference)
            ->build();

        $this->assertCount(1, $resume->references);
        $this->assertSame($reference, $resume->references[0]);
    }

    public function testCanAddProjects(): void
    {
        $project = new Project(
            name: 'Resume Builder',
        );

        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addProject($project)
            ->build();

        $this->assertCount(1, $resume->projects);
        $this->assertSame($project, $resume->projects[0]);
    }

    public function testCanBuildCompleteResume(): void
    {
        $resume = (new ResumeBuilder())
            ->basics($this->basics)
            ->addWork(new Work('Acme', 'Dev', startDate: new DateTimeImmutable('2020-01-01')))
            ->addSkill(new Skill('PHP'))
            ->build();

        $this->assertSame($this->basics, $resume->basics);
        $this->assertCount(1, $resume->work);
        $this->assertCount(1, $resume->skills);
    }

    public function testThrowsExceptionWhenBuildingWithoutBasics(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Basics section is required');

        (new ResumeBuilder())->build();
    }

    public function testBuilderMethodsReturnSelfForChaining(): void
    {
        $builder = new ResumeBuilder();
        $this->assertSame($builder, $builder->basics($this->basics));
        $this->assertSame($builder, $builder->addWork(new Work('Acme', 'Dev', startDate: new DateTimeImmutable('2020-01-01'))));
    }
}
