# Examples

## Creating a Resume

```php
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\Enums\Network;

$builder = new ResumeBuilder();

$builder->basics()
    ->setName('Steve McDougall')
    ->setLabel('Software Engineer')
    ->setEmail('steve@example.com')
    ->setUrl('https://steve.com')
    ->setSummary('Experienced PHP developer focused on developer experience.')
    ->setLocation(new Location(
        city: 'Caerphilly',
        countryCode: 'UK',
        region: 'Wales',
    ))
    ->addProfile(new Profile(
        network: Network::GITHUB,
        username: 'juststeveking',
        url: 'https://github.com/juststeveking',
    ));

$builder->addWork()
    ->setName('Acme Corp')
    ->setPosition('Senior Developer')
    ->setStartDate('2022-01-01')
    ->setSummary('Leading development of core products.')
    ->addHighlight('Implemented JSON Resume support');

$resume = $builder->build();
```

## Validating a Resume

```php
use JustSteveKing\Resume\Services\Validator;

$validator = new Validator();
try {
    $validator->validate($resume);
    echo "Resume is valid!";
} catch (ValidationException $e) {
    echo "Validation failed: " . $e->getMessage();
}
```

## Exporting to Markdown

```php
use JustSteveKing\Resume\Exporters\MarkdownExporter;

$exporter = new MarkdownExporter();
$markdown = $exporter->export($resume);
file_put_contents('resume.md', $markdown);
```

## Analyzing Career History

```php
use JustSteveKing\Resume\Services\CareerAnalyzer;

$analyzer = new CareerAnalyzer();
$analysis = $analyzer->analyze($resume);
echo "Total years of experience: " . $analysis->yearsOfExperience;
```

## Translating a Resume

```php
use JustSteveKing\Resume\Services\Translator;

$translator = new Translator();
$welshResume = $translator->translate($resume, 'cy');
```
