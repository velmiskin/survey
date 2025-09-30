<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class QuestionNotFoundException extends \RuntimeException
{
    public function __construct(string $message = 'Question not found')
    {
        parent::__construct($message);
    }
}