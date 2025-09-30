<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class SurveyCannotBeModifiedException extends \DomainException
{
    public function __construct(string $message = 'Survey cannot be modified in its current state')
    {
        parent::__construct($message);
    }
}