<?php

declare(strict_types=1);

namespace App\IO\User\Http\Controller;

use App\Component\User\Infrastructure\Doctrine\Entity\User;
use App\IO\User\Http\DTO\Response\UserResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class MeController extends AbstractController
{
    #[Route('/api/auth/me', name: 'api_auth_me', methods: ['GET'])]
    #[OA\Get(
        description: 'Returns the currently authenticated user information',
        summary: 'Get current user',
        security: [['Bearer' => []]],
        tags: ['Authentication'],
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Current user information',
        content: new Model(type: UserResponse::class),
    )]
    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $response = new UserResponse(
            id: $user->getId()->toString(),
            email: $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            role: $user->getRole(),
        );

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
