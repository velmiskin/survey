<?php

declare(strict_types=1);

namespace App\Tests\Application\User;

use App\Tests\Application\ApplicationTestCase;
use App\Tests\Factory\UserFactory;

final class RegistrationTest extends ApplicationTestCase
{
    public function testRegisterUserSuccessfully(): void
    {
        $this->client()
            ->url('api_auth_register')
            ->post([
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'role' => 'ROLE_PATIENT',
            ]);

        self::assertResponseStatusCodeSame(201);

        $response = $this->client()->decode();
        self::assertArrayHasKey('message', $response);
        self::assertSame('User registered successfully', $response['message']);
    }

    public function testRegisterUserWithDuplicateEmail(): void
    {
        $existingUser = UserFactory::createOne();

        $this->client()
            ->url('api_auth_register')
            ->post([
                'email' => $existingUser->getEmail(),
                'password' => 'password123',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'role' => 'ROLE_PATIENT',
            ]);

        self::assertResponseStatusCodeSame(409);

        $response = $this->client()->decode();
        self::assertArrayHasKey('error', $response);
        self::assertSame('EMAIL_ALREADY_EXISTS', $response['error']);
        self::assertSame('email', $response['field']);
    }

    public function testRegisterUserWithInvalidEmail(): void
    {
        $this->client()
            ->url('api_auth_register')
            ->post([
                'email' => 'invalid-email',
                'password' => 'password123',
                'firstName' => 'John',
                'lastName' => 'Doe',
            ]);

        self::assertResponseStatusCodeSame(422);
    }

    public function testRegisterUserWithShortPassword(): void
    {
        $this->client()
            ->url('api_auth_register')
            ->post([
                'email' => 'user@example.com',
                'password' => 'short',
                'firstName' => 'John',
                'lastName' => 'Doe',
            ]);

        self::assertResponseStatusCodeSame(422);
    }
}
