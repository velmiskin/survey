<?php

declare(strict_types=1);

namespace App\IO\User\Http\DTO\Response;

use App\Component\User\Domain\Entity\User;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
final readonly class UserResponse
{
    public function __construct(
        #[OA\Property(description: 'User UUID', example: '550e8400-e29b-41d4-a716-446655440000')]
        public string $id,

        #[OA\Property(example: 'user@example.com')]
        public string $email,

        #[OA\Property(example: 'John')]
        public string $firstName,

        #[OA\Property(example: 'Doe')]
        public string $lastName,

        #[OA\Property(description: 'User role', enum: [
            'ROLE_PATIENT',
            'ROLE_ADMIN',
            'ROLE_EMPLOYEE',
        ], example: 'ROLE_PATIENT')]
        public string $role,
    ) {
    }

    public static function fromDomain(User $user): self
    {
        return new self(
            id: $user->getId()->toString(),
            email: (string) $user->getEmail(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            role: (string) $user->getRole(),
        );
    }
}
