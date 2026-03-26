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
the [JSON Resume](https://jsonresume.org/) schema. It offers a fluent builder interface, data validation, and easy
serialization to JSON.

## Requirements

- PHP 8.4 or higher
- Composer

## Installation

You can install the package via composer:

```bash
composer require juststeveking/resume-php
```

## Usage

### Building a Basic Résumé

```php
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;

// Create the basics section
$basics = new Basics(
    name: 'John Doe',
    label: 'Software Engineer',
    email: 'john@example.com',
    url: 'https://johndoe.com',
    summary: 'Experienced software engineer with 5+ years in web development.',
    location: new Location(
        address: '123 Main St',
        postalCode: '94105',
        city: 'San Francisco',
        countryCode: 'US',
        region: 'CA',
    ),
    profiles: [
        new Profile(Network::GitHub, 'johndoe', 'https://github.com/johndoe'),
        new Profile(Network::LinkedIn, 'johndoe', 'https://linkedin.com/in/johndoe'),
    ],
);

// Build the résumé
$resume = (new ResumeBuilder())
    ->basics($basics)
    ->build();

// Convert to JSON
$json = json_encode($resume, JSON_PRETTY_PRINT);
```

### Adding Work Experience

```php
use JustSteveKing\Resume\DataObjects\Work;

$resume = (new ResumeBuilder())
    ->basics($basics)
    ->addWork(new Work(
        name: 'Tech Corp',
        position: 'Senior Developer',
        startDate: '2020-01-01',
        endDate: '2023-12-31',
        summary: 'Led development of core platform features',
        highlights: ['Improved performance by 40%', 'Mentored junior developers'],
    ))
    ->addWork(new Work(
        name: 'Startup Inc',
        position: 'Full Stack Developer',
        startDate: '2018-01-01',
        endDate: '2019-12-31',
    ))
    ->build();
```

### Adding Education

```php
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\Enums\EducationLevel;

$resume->addEducation(new Education(
    institution: 'University of Technology',
    area: 'Computer Science',
    studyType: EducationLevel::Bachelor,
    startDate: '2014-09-01',
    endDate: '2018-06-01',
));
```

### Adding Skills

```php
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\Enums\SkillLevel;

$resume->addSkill(new Skill(
    name: 'PHP',
    level: SkillLevel::Expert,
    keywords: ['Laravel', 'Symfony', 'API Development'],
));
```

### Complete Example

For a complete example of building a résumé with all available sections, see
the [example resume](resume/full.json) file or check the integration tests.

## Features

- **Type-Safe Builder Pattern**: Fluent interface for building resumes
- **Data Validation**: Built-in validation for emails, URLs, and other fields
- **JSON Resume Schema Compliance**: Ensures output follows the JSON Resume standard
- **Comprehensive Data Objects**: Structured classes for all resume components
- **Enums for Common Values**: Pre-defined enums for skill levels, education types, and social networks
- **Performance Optimized**: Efficiently handles large resumes with many entries
- **Summary Generation**: Generate summaries of resume content

## Available Components

- Basics (Personal Information)
- Work Experience
- Volunteer Experience
- Education
- Skills
- Languages
- Interests
- Projects
- Publications
- Awards
- Certificates
- References

## JSON-LD Transformation

Resume PHP provides a method to transform resume data into `JSON-LD` format using the `toJsonLd()` method on the
`Resume` object.

JSON-LD (JavaScript Object Notation for Linked Data) is a lightweight format for structuring data that is easily
understood by search engines and web services.

### How it works:

The `toJsonLd()` method converts your résumé into a structured array following the `schema.org/Person` specification. It
extracts key fields such as:

- name
- job title
- website
- social profiles
- skills

and formats them for semantic web consumption.

### Why it’s important:

- **SEO & Discoverability**: JSON-LD enables search engines to better understand and index your résumé, improving
  visibility in search results.
- **Interoperability**: Many platforms and tools support JSON-LD, making it easier to share and integrate your resume
  data.
- **Standardization**: Using schema.org ensures your résumé follows widely accepted standards for personal data
  representation.

### Example

```php
$jsonLd = $resume->toJsonLd();
echo json_encode($jsonLd, JSON_PRETTY_PRINT);
```

This will output a structured JSON-LD object ready for embedding in web pages or sharing with compatible services.

## Exporting a Resume to Markdown

The Resume object includes a `toMarkdown()` method that allows you to export your resume to a clean, human-readable
Markdown format.

This is useful for:

- GitHub READMEs
- Static site content
- Terminal output
- Blog posts
- Generating printable formats via Markdown → PDF

### Basic Usage

```php
echo $resume->toMarkdown();
```

### Optional Configuration

You can customize the output using the options:

```php
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

Each section can be enabled/disabled individually:

| Section | Option Key |
|---------|------------|
| Name, title, summary | `basics` |
| Email, website, location | `contact` |
| Social links | `profiles` |
| Work experience | `work` |
| Education history | `education` |
| Skills list | `skills` |
| Spoken languages | `languages` |

### Example Output

```markdown
# Steve McDougall
**API Consultant**

Helping dev teams build better APIs.

📧 Email: [steve@example.com](mailto:steve@example.com)
🌍 Website: [https://juststeveking.com](https://juststeveking.com)
📍 Location: Wales, GB

### 🔗 Social Profiles
- [GitHub](https://github.com/JustSteveKing)

## 💼 Work Experience

### API Consultant at Freelance
_2023-01 → Present_
Consulting on large-scale API infrastructure.
- Designed a scalable Laravel API Gateway.
- Reduced API response times by 40%.

## 🛠 Skills
- **PHP**: Laravel, Symfony, Octane
```

## Job Description Builder

The library also includes a `JobDescriptionBuilder` for creating structured job descriptions
following [the in progress JSON Job Description schema](https://jsonresume.org/job-description-schema/).

```php
use JustSteveKing\Resume\Builders\JobDescriptionBuilder;

$jobDescription = (new JobDescriptionBuilder())
    ->title('Senior PHP Developer')
    ->company('Tech Corp')
    ->location('Remote')
    ->salary('$100,000 - $130,000')
    ->addRequirement('5+ years of PHP experience')
    ->addRequirement('Experience with Laravel or Symfony')
    ->addBenefit('Remote work')
    ->addBenefit('Flexible hours')
    ->build();
```

## Development

### Testing

```bash
composer test
```

### Static Analysis

```bash
composer stan
```

### Code Style

```bash
composer pint
```

### Refactoring

```bash
composer refactor
```

## CI/CD Workflows

This project uses GitHub Actions for continuous integration and continuous deployment. The following workflows are set
up:

### Tests

[![Tests](https://github.com/juststeveking/resume-php/actions/workflows/tests.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/tests.yml)

Runs PHPUnit tests on PHP 8.4 to ensure all functionality works as expected.

### Static Analysis

[![Static Analysis](https://github.com/juststeveking/resume-php/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/static-analysis.yml)

Use PHPStan to perform static code analysis and catch potential bugs.

### Code Style

[![Code Style](https://github.com/juststeveking/resume-php/actions/workflows/code-style.yml/badge.svg)](https://github.com/juststeveking/resume-php/actions/workflows/code-style.yml)

Ensures code follows the defined style rules using Laravel Pint.

### Dependency Updates

The project uses GitHub's Dependabot to automatically check for dependency updates and create pull requests when updates
are available.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Steve McDougall](https://github.com/juststeveking)
- [All Contributors](../../contributors)
