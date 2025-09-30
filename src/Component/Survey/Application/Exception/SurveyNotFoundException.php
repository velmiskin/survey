<?php

declare(strict_types=1);

namespace App\Component\Survey\Application\Exception;

use Ramsey\Uuid\UuidInterface;

final class SurveyNotFoundException extends \RuntimeException
{
    public function __construct(UuidInterface $surveyId)
    {
        parent::__construct(\sprintf('Survey with id "%s" not found.', $surveyId->toString()));
    }
}