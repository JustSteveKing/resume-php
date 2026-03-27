<?php

declare(strict_types=1);

namespace Tests\Services;

use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\Services\Validator;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;
use Tests\PackageTestCase;

final class ValidatorTest extends PackageTestCase
{
    public function testValidatesAValidResume(): void
    {
        $resume = $this->buildCompleteResume();
        $validator = new Validator();

        $this->assertTrue($validator->validate($resume));
    }

    public function testCanValidateViaResumeMethod(): void
    {
        $resume = $this->buildCompleteResume();

        $this->assertTrue($resume->validate());
    }

    public function testThrowsExceptionForInvalidEmailInBasics(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format: invalid-email');

        new Email('invalid-email');
    }
}
