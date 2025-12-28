<?php

namespace App\IO\User\Http\Exception\Mapping;

use App\Component\User\Domain\Exception\NonUniqueEmailException;
use App\IO\Common\Http\Exception\Mapping\ExceptionMappingInterface;
use Symfony\Component\HttpFoundation\Response;

class UserExceptionMapping implements ExceptionMappingInterface
{
    public function supports(\DomainException $exception): bool
    {
        return $exception instanceof NonUniqueEmailException;
    }

    public function getField(): ?string
    {
        return 'email';
    }

    public function getErrorCode(): string
    {
        return 'EMAIL_ALREADY_EXISTS';
    }

    public function getHttpStatus(): int
    {
        return Response::HTTP_CONFLICT;
    }

    public function getMessage(\DomainException $exception): string
    {
        return $exception->getMessage();
    }
}
