<?php

declare(strict_types=1);

namespace App\IO\User\Http\Controller;

use App\Common\Application\Bus\CommandBusInterface;
use App\Component\User\Application\Command\RegisterUserCommand;
use App\IO\User\Http\DTO\Request\RegisterRequest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    #[Route('/api/auth/register', name: 'api_auth_register', methods: ['POST'])]
    #[OA\Post(
        description: 'Create a new user account',
        summary: 'Register new user',
        tags: ['Authentication'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: RegisterRequest::class),
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User registered successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'User registered successfully'),
            ],
        ),
    )]
    public function __invoke(
        #[MapRequestPayload] RegisterRequest $request,
    ): JsonResponse {
        $this->commandBus->handle(
            new RegisterUserCommand(
                uuid: Uuid::uuid4(),
                firstName: $request->firstName,
                lastName: $request->lastName,
                email: $request->email,
                password: $request->password,
                role: $request->role,
            )
        );

        return new JsonResponse(
            ['message' => 'User registered successfully'],
            Response::HTTP_CREATED
        );
    }
}
