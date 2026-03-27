---
name: resume-php
description: Type-safe PHP library for building, validating, and exporting JSON Resumes. Use when the user wants to programmatically create a resume, validate a JSON resume against the schema, or export a resume to JSON-LD, Markdown, or YAML in PHP.
---

# Resume PHP Skill

This skill provides guidance and workflows for using the `resume-php` library to work with JSON Resumes.

## Core Workflows

### 1. Building a Resume
Use the fluent `ResumeBuilder` to construct a resume.
- **Reference:** See [references/api-reference.md](references/api-reference.md) for available builder methods.
- **Examples:** See [references/examples.md](references/examples.md) for full building examples.

### 2. Validating a Resume
Use the `Validator` service to ensure your resume matches the JSON Resume schema.
- **Service:** `JustSteveKing\Resume\Services\Validator`
- **Example:** `(new Validator())->validate($resume);`

### 3. Exporting to Different Formats
The library supports exporting to JSON-LD, Markdown, and YAML.
- **Exporters:**
    - `JsonLdExporter`: For SEO-friendly JSON-LD.
    - `MarkdownExporter`: For standard Markdown output.
    - `YamlExporter`: For YAML formatting.

### 4. Career Analysis & Translation
- **Analysis:** `CareerAnalyzer` provides insights into work history.
- **Translation:** `Translator` supports English (`en`) and Welsh (`cy`).

## API & Documentation
- **Full API Reference:** [references/api-reference.md](references/api-reference.md)
- **Code Examples:** [references/examples.md](references/examples.md)
