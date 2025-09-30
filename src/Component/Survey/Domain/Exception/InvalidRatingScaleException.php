<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\Exception;

final class InvalidRatingScaleException extends \InvalidArgumentException
{
    public function __construct(string $message = 'Invalid rating scale configuration')
    {
        parent::__construct($message);
    }
}