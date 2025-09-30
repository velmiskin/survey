<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class SurveyCannotBePublishedException extends \DomainException
{
    public function __construct(string $message = 'Survey cannot be published in its current state')
    {
        parent::__construct($message);
    }
}