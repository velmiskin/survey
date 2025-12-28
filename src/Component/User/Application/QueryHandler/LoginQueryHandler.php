<?php

declare(strict_types=1);

namespace App\Component\User\Application\QueryHandler;

use App\Common\Application\Query\QueryHandlerInterface;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Application\DTO\LoginResult;
use App\Component\User\Application\Exception\InvalidCredentialsException;
use App\Component\User\Application\Query\LoginQuery;
use App\Component\User\Application\Service\TokenGeneratorInterface;
use App\Component\User\Domain\Presenter\UserPresenterInterface;

final readonly class LoginQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserPresenterInterface $userPresenter,
        private TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function __invoke(LoginQuery $query): LoginResult
    {
        // Load user by email
        $user = $this->userPresenter->findByEmail(new Email($query->email));

        if (null === $user) {
            throw new InvalidCredentialsException();
        }

        // Verify password using HashedPassword value object
        if (!$user->getPassword()->equals($query->password)) {
            throw new InvalidCredentialsException();
        }

        // Generate authentication token
        $token = $this->tokenGenerator->generate($user);

        return new LoginResult(
            token: $token,
            user: $user,
        );
    }
}
