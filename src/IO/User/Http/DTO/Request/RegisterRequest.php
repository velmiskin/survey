<?php

declare(strict_types=1);

namespace App\IO\User\Http\DTO\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'RegisterRequest',
    required: ['email', 'password', 'firstName', 'lastName'],
)]
final readonly class RegisterRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'Invalid email address')]
        #[OA\Property(example: 'user@example.com')]
        public string $email,

        #[Assert\NotBlank(message: 'Password is required')]
        #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters')]
        #[OA\Property(description: 'Password (minimum 8 characters)', example: 'password123')]
        public string $password,

        #[Assert\NotBlank(message: 'First name is required')]
        #[OA\Property(example: 'John')]
        public string $firstName,

        #[Assert\NotBlank(message: 'Last name is required')]
        #[OA\Property(example: 'Doe')]
        public string $lastName,

        #[Assert\Choice(choices: ['ROLE_PATIENT', 'ROLE_ADMIN', 'ROLE_EMPLOYEE'], message: 'Invalid role')]
        #[OA\Property(description: 'User role', example: 'ROLE_PATIENT', enum: [
            'ROLE_PATIENT',
            'ROLE_ADMIN',
            'ROLE_EMPLOYEE',
        ])]
        public string $role = 'ROLE_PATIENT',
    ) {
    }
}
