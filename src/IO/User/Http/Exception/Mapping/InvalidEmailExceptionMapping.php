<?php

declare(strict_types=1);

namespace App\IO\User\Http\Exception\Mapping;

use App\Component\User\Domain\Exception\InvalidEmailException;
use App\IO\Common\Http\Exception\Mapping\ExceptionMappingInterface;
use Symfony\Component\HttpFoundation\Response;

final class InvalidEmailExceptionMapping implements ExceptionMappingInterface
{
    public function supports(\DomainException $exception): bool
    {
        return $exception instanceof InvalidEmailException;
    }

    public function getField(): ?string
    {
        return 'email';
    }

    public function getErrorCode(): string
    {
        return 'INVALID_EMAIL';
    }

    public function getHttpStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getMessage(\DomainException $exception): string
    {
        return $exception->getMessage();
    }
}
