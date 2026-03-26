<?php

declare(strict_types=1);

namespace Tests\Services;

use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Services\Validator;
use JustSteveKing\Resume\Exceptions\ValidationException;
use JustSteveKing\Resume\DataObjects\Basics;
use Tests\PackageTestCase;

final class ValidatorTest extends PackageTestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    public function test_it_validates_a_valid_resume(): void
    {
        $resume = $this->buildCompleteResume();
        $this->assertTrue($this->validator->validate($resume));
    }

    public function test_it_can_validate_via_resume_method(): void
    {
        $resume = $this->buildCompleteResume();
        $this->assertTrue($resume->validate());
    }
}
