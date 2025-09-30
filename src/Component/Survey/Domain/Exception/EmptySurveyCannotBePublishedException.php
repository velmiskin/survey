<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class EmptySurveyCannotBePublishedException extends \DomainException
{
    public function __construct(string $message = 'Survey must have at least one question to be published')
    {
        parent::__construct($message);
    }
}