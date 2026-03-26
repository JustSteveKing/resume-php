<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Services;

use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Exceptions\ValidationException;
use Opis\JsonSchema\Validator as JsonValidator;
use Opis\JsonSchema\Errors\ErrorFormatter;

final readonly class Validator
{
    private JsonValidator $validator;

    public function __construct(
        null|JsonValidator $validator = null,
    ) {
        $this->validator = $validator ?? new JsonValidator();
    }

    /**
     * @param Resume $resume
     * @return bool
     * @throws ValidationException
     */
    public function validate(Resume $resume): bool
    {
        // Encode and decode to get standard object for validator
        $data = json_decode(
            json: json_encode($resume) ?: '{}',
            associative: false,
        );

        $schemaPath = dirname(__DIR__, 2) . '/resources/schema.json';

        if (! file_exists($schemaPath)) {
            throw new ValidationException(
                message: "Schema file not found at [{$schemaPath}].",
            );
        }

        $schema = file_get_contents($schemaPath) ?: '{}';

        $result = $this->validator->validate($data, $schema);

        if ($result->isValid()) {
            return true;
        }

        $error = $result->error();
        $formatter = new ErrorFormatter();

        // Get the formatted errors
        $errors = $formatter->format($error);

        throw new ValidationException(
            message: 'Resume validation failed.',
            errors: (array) $errors,
        );
    }
}
