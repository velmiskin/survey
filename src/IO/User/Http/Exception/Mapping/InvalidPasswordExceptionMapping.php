<?php

declare(strict_types=1);

namespace App\IO\User\Http\Exception\Mapping;

use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\IO\Common\Http\Exception\Mapping\ExceptionMappingInterface;
use Symfony\Component\HttpFoundation\Response;

final class InvalidPasswordExceptionMapping implements ExceptionMappingInterface
{
    public function supports(\DomainException $exception): bool
    {
        return $exception instanceof InvalidPasswordException;
    }

    public function getField(): ?string
    {
        return 'password';
    }

    public function getErrorCode(): string
    {
        return 'INVALID_PASSWORD';
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
