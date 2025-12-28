<?php

declare(strict_types=1);

namespace App\IO\User\Http\Controller;

use App\Common\Application\Bus\QueryBusInterface;
use App\Component\User\Application\DTO\LoginResult;
use App\Component\User\Application\Exception\InvalidCredentialsException;
use App\Component\User\Application\Query\LoginQuery;
use App\IO\User\Http\DTO\Request\LoginRequest;
use App\IO\User\Http\DTO\Response\LoginResponse;
use App\IO\User\Http\DTO\Response\UserResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    #[OA\Post(
        description: 'Authenticate user with email and password to obtain JWT token',
        summary: 'User login',
        tags: ['Authentication'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LoginRequest::class),
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful login',
        content: new Model(type: LoginResponse::class),
    )]
    public function __invoke(
        #[MapRequestPayload] LoginRequest $request,
    ): JsonResponse {
        try {
            /** @var LoginResult $result */
            $result = $this->queryBus->query(
                new LoginQuery(
                    email: $request->email,
                    password: $request->password,
                )
            );

            // Build API response from application layer result
            $response = new LoginResponse(
                token: $result->token,
                user: UserResponse::fromDomain($result->user),
            );

            return new JsonResponse($response, Response::HTTP_OK);
        } catch (HandlerFailedException $e) {
            // Unwrap the original exception from Messenger
            $previous = $e->getPrevious();

            if ($previous instanceof InvalidCredentialsException) {
                return new JsonResponse(
                    ['error' => $previous->getMessage()],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            throw $e;
        }
    }
}
