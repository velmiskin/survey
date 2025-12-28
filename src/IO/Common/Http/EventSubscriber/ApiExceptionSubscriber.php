<?php

declare(strict_types=1);

namespace App\IO\Common\Http\EventSubscriber;

use App\IO\Common\Http\Exception\Mapping\ExceptionMappingInterface;
use App\IO\Common\Http\Exception\Mapping\ExceptionMappingResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final readonly class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ExceptionMappingResolver $exceptionMappingResolver,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Only handle API errors (routes starting with /api)
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Unwrap HandlerFailedException from Messenger
        $domainException = $this->unwrapDomainException($exception);

        if (null !== $domainException) {
            $mapping = $this->exceptionMappingResolver->resolve($domainException);

            if (null !== $mapping) {
                $response = $this->createErrorResponse($mapping, $domainException);
                $event->setResponse($response);

                return;
            }
        }
    }

    private function unwrapDomainException(\Throwable $exception): ?\DomainException
    {
        // Handle Messenger's HandlerFailedException
        if ($exception instanceof HandlerFailedException) {
            $previous = $exception->getPrevious();
            if ($previous instanceof \DomainException) {
                return $previous;
            }
        }

        // Direct DomainException
        if ($exception instanceof \DomainException) {
            return $exception;
        }

        return null;
    }

    private function createErrorResponse(
        ExceptionMappingInterface $mapping,
        \DomainException $exception,
    ): JsonResponse {
        $data = [
            'error' => $mapping->getErrorCode(),
            'message' => $mapping->getMessage($exception),
        ];

        $field = $mapping->getField();
        if (null !== $field) {
            $data['field'] = $field;
        }

        return new JsonResponse($data, $mapping->getHttpStatus());
    }
}
