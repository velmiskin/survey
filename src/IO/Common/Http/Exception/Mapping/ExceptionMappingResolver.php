<?php

namespace App\IO\Common\Http\Exception\Mapping;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class ExceptionMappingResolver
{
    public function __construct(
        #[AutowireIterator('app.exception_mapping')]
        private iterable $mappings,
    ) {
    }

    public function resolve(\DomainException $exception): ?ExceptionMappingInterface
    {
        foreach ($this->mappings as $mapping) {
            if ($mapping->supports($exception)) {
                return $mapping;
            }
        }

        return null;
    }
}