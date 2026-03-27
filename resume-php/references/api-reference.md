# API Reference

## Builders

### `ResumeBuilder`
- `basics(?Basics $basics = null): ResumeBuilder|BasicsBuilder`: Sets the basics or returns a `BasicsBuilder`.
- `addWork(?Work $work = null): ResumeBuilder|WorkBuilder`: Adds a work experience or returns a `WorkBuilder`.
- `addVolunteer(Volunteer $volunteer): ResumeBuilder`
- `addEducation(Education $education): ResumeBuilder`
- `addAward(Award $award): ResumeBuilder`
- `addCertificate(Certificate $certificate): ResumeBuilder`
- `addPublication(Publication $publication): ResumeBuilder`
- `addSkill(Skill $skill): ResumeBuilder`
- `addLanguage(Language $language): ResumeBuilder`
- `addInterest(Interest $interest): ResumeBuilder`
- `addReference(Reference $reference): ResumeBuilder`
- `addProject(Project $project): ResumeBuilder`
- `build(): Resume`: Builds the final `Resume` object.

### `BasicsBuilder`
- `setName(string $name): self`
- `setLabel(string $label): self`
- `setImage(string $image): self`
- `setEmail(string $email): self`
- `setPhone(string $phone): self`
- `setUrl(string $url): self`
- `setSummary(string $summary): self`
- `setLocation(Location $location): self`
- `addProfile(Profile $profile): self`
- `build(): Basics`

### `WorkBuilder`
- `setName(string $name): self`
- `setPosition(string $position): self`
- `setUrl(string $url): self`
- `setStartDate(DateTimeInterface|string $startDate): self`
- `setEndDate(DateTimeInterface|string|null $endDate): self`
- `setSummary(string $summary): self`
- `addHighlight(string $highlight): self`
- `build(): Work`

## Services

### `Validator`
- `validate(Resume $resume): bool`: Validates the `Resume` object against the JSON Resume schema.

### `CareerAnalyzer`
- `analyze(Resume $resume): CareerAnalysis`: Analyzes the resume for gaps, trends, and other insights.

### `Translator`
- `translate(Resume $resume, string $targetLanguage): Resume`: Translates the resume into another language (supported: `en`, `cy`).

## Exporters

### `JsonLdExporter`
- `export(Resume $resume): string`

### `MarkdownExporter`
- `export(Resume $resume): string`

### `YamlExporter`
- `export(Resume $resume): string`
