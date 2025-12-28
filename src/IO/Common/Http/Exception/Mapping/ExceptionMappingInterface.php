<?php

namespace App\IO\Common\Http\Exception\Mapping;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.exception_mapping')]
interface ExceptionMappingInterface
{
    /**
     * Check if this mapping handles the given exception.
     */
    public function supports(\DomainException $exception): bool;

    /**
     * Get the field name if this is a field-related error
     * Return null for non-field errors (Type 2).
     */
    public function getField(): ?string;

    /**
     * Get the error code (e.g., 'INVALID_EMAIL', 'USER_NOT_FOUND').
     */
    public function getErrorCode(): string;

    /**
     * Get the HTTP status code.
     */
    public function getHttpStatus(): int;

    /**
     * Get the user-facing error message.
     */
    public function getMessage(\DomainException $exception): string;
}
