<?php

declare(strict_types=1);

namespace App\IO\User\Http\DTO\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
final readonly class LoginResponse
{
    public function __construct(
        #[OA\Property(description: 'JWT authentication token', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...')]
        public string $token,

        #[OA\Property(ref: '#/components/schemas/UserResponse')]
        public UserResponse $user,
    ) {
    }
}
