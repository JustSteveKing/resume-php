# GEMINI.md - Resume PHP

## Project Overview
**Resume PHP** is a type-safe PHP library designed for building and working with the [JSON Resume](https://jsonresume.org/) schema. It provides a fluent builder interface, data validation, and utilities for exporting resumes to various formats including JSON, JSON-LD, and Markdown.

### Core Technologies
- **Language:** PHP 8.4+ (Strict typing enabled)
- **Dependency Management:** Composer
- **Validation:** `opis/json-schema`
- **Testing:** PHPUnit 12.1+
- **Static Analysis:** PHPStan 2.1+
- **Code Style:** Laravel Pint 1.24+
- **Automated Refactoring:** Rector 2.1+

### Architecture & Patterns
The project follows a data-oriented architectural style with a focus on type safety and immutability:
- **DataObjects (`src/DataObjects/`):** Final readonly classes that represent individual components of a resume (e.g., `Basics`, `Work`, `Education`). These implement `JsonSerializable` for schema-compliant output.
- **Builders (`src/Builders/`):** Provide a fluent interface (`ResumeBuilder`, `JobDescriptionBuilder`) to construct complex objects step-by-step.
- **Attributes (`src/Attributes/`):** Custom PHP attributes like `#[Field]` are used to map class properties to their corresponding JSON schema fields.
- **Concerns (`src/Concerns/`):** Traits used for shared logic, particularly for validating common data types like emails and URLs.
- **Enums (`src/Enums/`):** Typed enumerations for fixed schema values such as `SkillLevel`, `EducationLevel`, and `Network`.

## Building and Running

### Installation
Ensure you have PHP 8.4 and Composer installed.
```bash
composer install
```

### Key Development Commands
| Task | Command | Description |
|------|---------|-------------|
| **Tests** | `composer test` | Runs the PHPUnit test suite with `--testdox`. |
| **Static Analysis** | `composer stan` | Runs PHPStan at the highest possible level. |
| **Code Style** | `composer pint` | Fixes code style issues using Laravel Pint. |
| **Lint Check** | `composer lint` | Checks code style without applying fixes. |
| **Refactor** | `composer refactor` | Applies Rector rules for automated code improvements. |
| **Dry Run Refactor** | `composer rector` | Shows proposed Rector changes without applying them. |

## Development Conventions

### Coding Style
- **Strict Typing:** All PHP files MUST include `declare(strict_types=1);`.
- **Immutability:** Use `readonly` for DataObjects and internal properties whenever possible.
- **Naming:** Follow PSR-12/PER Coding Style (enforced by Pint).
- **Documentation:** Use PHPDoc for complex array shapes and generics where native types are insufficient.

### Testing Practices
- **PHPUnit:** Tests are located in the `tests/` directory.
- **Test Structure:** Mirror the `src/` directory structure within `tests/`.
- **Integration Tests:** Use `tests/Integration/` for verifying end-to-end flows like JSON-LD or Markdown generation.

### Contribution Workflow
1. **Branching:** Use descriptive branch names (e.g., `feature/add-json-ld-export`).
2. **Validation:** Ensure `composer test`, `composer stan`, and `composer lint` pass before submitting a PR.
3. **Refactoring:** Run `composer refactor` periodically to maintain code quality.
