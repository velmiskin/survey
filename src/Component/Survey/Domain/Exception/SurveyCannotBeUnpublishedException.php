<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class SurveyCannotBeUnpublishedException extends \DomainException
{
    public function __construct(string $message = 'Survey cannot be unpublished in its current state')
    {
        parent::__construct($message);
    }
}