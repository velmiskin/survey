<?php

declare(strict_types=1);

namespace App\Tests\Application\User;

use App\Tests\Application\ApplicationTestCase;
use App\Tests\Factory\UserFactory;

final class AuthenticationTest extends ApplicationTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $user = UserFactory::createOne();

        $this->client()
            ->url('api_auth_login')
            ->post([
                'email' => $user->getEmail(),
                'password' => 'password',
            ]);

        self::assertResponseStatusCodeSame(200);

        $response = $this->client()->decode();
        self::assertArrayHasKey('token', $response);
        self::assertArrayHasKey('user', $response);
        self::assertSame($user->getEmail(), $response['user']['email']);
        self::assertSame($user->getFirstName(), $response['user']['firstName']);
        self::assertSame($user->getLastName(), $response['user']['lastName']);
    }

    public function testLoginWithInvalidEmail(): void
    {
        $this->client()
            ->url('api_auth_login')
            ->post([
                'email' => 'nonexistent@example.com',
                'password' => 'password123',
            ]);

        self::assertResponseStatusCodeSame(401);

        $response = $this->client()->decode();
        self::assertArrayHasKey('error', $response);
        self::assertSame('Invalid email or password.', $response['error']);
    }

    public function testLoginWithInvalidPassword(): void
    {
        $user = UserFactory::createOne();

        $this->client()
            ->url('api_auth_login')
            ->post([
                'email' => $user->getEmail(),
                'password' => 'wrongpassword',
            ]);

        self::assertResponseStatusCodeSame(401);

        $response = $this->client()->decode();
        self::assertArrayHasKey('error', $response);
        self::assertSame('Invalid email or password.', $response['error']);
    }

    public function testGetCurrentUserWithValidToken(): void
    {
        $user = UserFactory::createOne();

        $this->client()->actingAs($user);
        $this->client()->url('api_auth_me')->get();

        self::assertResponseStatusCodeSame(200);

        $response = $this->client()->decode();
        self::assertSame($user->getEmail(), $response['email']);
        self::assertSame($user->getFirstName(), $response['firstName']);
        self::assertSame($user->getLastName(), $response['lastName']);
    }

    public function testGetCurrentUserWithoutToken(): void
    {
        $this->client()->url('api_auth_me')->get();

        self::assertResponseStatusCodeSame(401);
    }

    public function testGetCurrentUserWithInvalidToken(): void
    {
        $this->client()
            ->withToken('invalid.token.here')
            ->url('api_auth_me')
            ->get();

        self::assertResponseStatusCodeSame(401);
    }
}
