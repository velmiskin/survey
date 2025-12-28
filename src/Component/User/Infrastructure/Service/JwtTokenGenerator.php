<?php

declare(strict_types=1);

namespace App\Component\User\Infrastructure\Service;

use App\Component\User\Application\Service\TokenGeneratorInterface;
use App\Component\User\Domain\Entity\User;
use App\Component\User\Infrastructure\Doctrine\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final readonly class JwtTokenGenerator implements TokenGeneratorInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserRepository $userRepository,
    ) {
    }

    public function generate(User $user): string
    {
        // Load the persisted Doctrine user for JWT token generation
        $doctrineUser = $this->userRepository->find($user->getId());

        if (!$doctrineUser) {
            throw new \RuntimeException('User not found in database');
        }

        return $this->jwtManager->create($doctrineUser);
    }
}
