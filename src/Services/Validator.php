<?php

declare(strict_types=1);

namespace JustSteveKing\Resume\Services;

use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Exceptions\ValidationException;
use Opis\JsonSchema\Validator as JsonValidator;

final class Validator
{
    private JsonValidator $validator;
    private string $schemaPath;

    public function __construct(?string $schemaPath = null)
    {
        $this->validator = new JsonValidator();
        $this->schemaPath = $schemaPath ?? __DIR__ . '/../../resources/schema.json';
    }

    /**
     * Validate the résumé against the JSON schema.
     *
     * @param Resume $resume
     * @return bool
     * @throws ValidationException
     */
    public function validate(Resume $resume): bool
    {
        $encoded = json_encode($resume);
        if (false === $encoded) {
             throw new ValidationException("Failed to encode resume to JSON");
        }
        $data = json_decode($encoded, false);
        
        $schemaContent = file_get_contents($this->schemaPath);
        if (false === $schemaContent) {
            throw new ValidationException("Failed to read schema file at {$this->schemaPath}");
        }
        $schema = json_decode($schemaContent, false);

        /** @var object|bool|string $schema */
        $result = $this->validator->validate($data, $schema);

        if ( ! $result->isValid()) {
            $error = $result->error();
            $path = implode('->', $error?->data()->fullPath() ?? []);
            $message = "Resume validation failed at {$path}: " . ($error?->keyword() ?? 'unknown');
            throw new ValidationException($message);
        }

        return true;
    }
}
