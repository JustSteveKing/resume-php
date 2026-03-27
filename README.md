# Resume PHP

A PHP library for building and working with the [JSON Resume](https://jsonresume.org/) schema.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juststeveking/resume-php.svg?style=flat-square)](https://packagist.org/packages/juststeveking/resume-php)
[![Total Downloads](https://img.shields.io/packagist/dt/juststeveking/resume-php.svg?style=flat-square)](https://packagist.org/packages/juststeveking/resume-php)
[![License](https://img.shields.io/packagist/l/juststeveking/resume-php.svg?style=flat-square)](./LICENSE)
[![Tests](https://github.com/juststeveking/resume-php/actions/workflows/tests.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/tests.yml)
[![Static Analysis](https://github.com/juststeveking/resume-php/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/static-analysis.yml)
[![Code Style](https://github.com/juststeveking/resume-php/actions/workflows/code-style.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/code-style.yml)

## Introduction

Resume PHP is a library that provides a type-safe way to build and work with resumes following
the [JSON Resume](https://jsonresume.org/) schema. It offers a fluent builder interface, rigorous data validation, and automated serialization to schema-compliant JSON.

## Requirements

- PHP 8.4 or higher
- Composer

## Installation

You can install the package via composer:

```bash
composer require juststeveking/resume-php
```

## Usage

### Building a Résumé

The library uses strictly-typed Data Objects and Value Objects to ensure your data is always valid and compliant with the schema.

```php
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

// Create the basics section using Value Objects for Email and URL
$basics = new Basics(
    name: 'John Doe',
    label: 'Software Engineer',
    email: new Email('john@example.com'),
    url: new Url('https://johndoe.com'),
    summary: 'Experienced software engineer with 5+ years in web development.',
    location: new Location(
        address: '123 Main St',
        postalCode: '94105',
        city: 'San Francisco',
        countryCode: 'US',
        region: 'CA',
    ),
    profiles: [
        new Profile(Network::GitHub, 'johndoe', new Url('https://github.com/johndoe')),
        new Profile(Network::LinkedIn, 'johndoe', new Url('https://linkedin.com/in/johndoe')),
    ],
);

// Build the résumé fluently
$resume = (new ResumeBuilder())
    ->basics($basics)
    ->addWork(new \JustSteveKing\Resume\DataObjects\Work(
        name: 'Tech Corp',
        position: 'Senior Developer',
        startDate: '2020-01-01',
        summary: 'Led development of core platform features',
        highlights: ['Improved performance by 40%', 'Mentored junior developers'],
    ))
    ->build();

// Validate against the official JSON schema
$isValid = $resume->validate();

// Convert to schema-compliant JSON
$json = json_encode($resume, JSON_PRETTY_PRINT);
```

### Hydrating from Existing Data

You can easily load an existing JSON résumé or array using the `ResumeFactory`.

```php
use JustSteveKing\Resume\Factories\ResumeFactory;

// From a JSON string
$resume = ResumeFactory::fromJson($jsonString);

// From an associative array
$resume = ResumeFactory::fromArray($data);
```

### Adding Education & Skills

```php
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\SkillLevel;

$resumeBuilder = (new ResumeBuilder())->basics($basics);

$resumeBuilder->addEducation(new Education(
    institution: 'University of Technology',
    area: 'Computer Science',
    studyType: EducationLevel::Bachelor,
    startDate: '2014-09-01',
    endDate: '2018-06-01',
));

$resumeBuilder->addSkill(new Skill(
    name: 'PHP',
    level: SkillLevel::Expert,
    keywords: ['Laravel', 'Symfony', 'API Development'],
));

$resume = $resumeBuilder->build();
```

## Features

- **Strictly Typed**: Leverages PHP 8.4 features like property promotion and readonly classes for robust data integrity.
- **Value Objects**: Uses `Email` and `Url` value objects to enforce data quality at the point of creation.
- **Fluent Builder**: A developer-friendly interface for constructing complex resumes step-by-step.
- **Schema Validation**: Built-in validation using `opis/json-schema` against the official JSON Resume specification.
- **Smart Serialization**: Automatically filters out `null` or empty optional fields to keep your JSON output clean.
- **Exporters**: Built-in support for transforming resumes to Markdown and JSON-LD (Schema.org).

## Exporting & Transformations

### JSON-LD (Semantic Web)

The `toJsonLd()` method converts your résumé into a structured array following the `schema.org/Person` specification.

```php
$jsonLd = $resume->toJsonLd();
echo json_encode($jsonLd, JSON_PRETTY_PRINT);
```

### Markdown Export

Generate a clean, human-readable Markdown version of your resume.

```php
// Basic export
echo $resume->toMarkdown();

// Custom configuration (enable/disable sections)
$markdown = $resume->toMarkdown([
    'basics' => true,
    'contact' => true,
    'profiles' => true,
    'work' => true,
    'education' => true,
    'skills' => true,
    'languages' => true,
]);
```

## Job Description Builder

Create structured job descriptions using the same fluent pattern.

```php
use JustSteveKing\Resume\Builders\JobDescriptionBuilder;

$jobDescription = (new JobDescriptionBuilder())
    ->name('Senior PHP Developer')
    ->location('Remote')
    ->description('Lead our backend transition to PHP 8.4')
    ->addHighlight('Competitive salary')
    ->addSkill('PHP')
    ->addTool('Docker')
    ->addResponsibility('Code reviews')
    ->build();
```

## Development

The project maintains high standards through automated tools:

- **Testing**: `composer test` (PHPUnit)
- **Static Analysis**: `composer stan` (PHPStan at Level 9)
- **Code Style**: `composer pint` (Laravel Pint)
- **Refactoring**: `composer refactor` (Rector)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Steve McDougall](https://github.com/juststeveking)
- [All Contributors](../../contributors)
