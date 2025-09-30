<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\ValueObject;

use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\ValueObject\HashedPassword;
use PHPUnit\Framework\TestCase;

final class HashedPasswordTest extends TestCase
{
    public function testCreateFromPlainPasswordValid(): void
    {
        $plainPassword = 'password123';
        $hashedPassword = HashedPassword::createFormPlain($plainPassword);

        $this->assertInstanceOf(HashedPassword::class, $hashedPassword);
        $this->assertTrue($hashedPassword->equals($plainPassword));
    }

    public function testCreateFromPlainPasswordTooShort(): void
    {
        $this->expectException(InvalidPasswordException::class);
        HashedPassword::createFormPlain('short');
    }

    public function testCreateFromPlainPasswordMinimumLength(): void
    {
        $plainPassword = '12345678';
        $hashedPassword = HashedPassword::createFormPlain($plainPassword);

        $this->assertTrue($hashedPassword->equals($plainPassword));
    }

    public function testPasswordVerificationWithCorrectPassword(): void
    {
        $plainPassword = 'testpassword123';
        $hashedPassword = HashedPassword::createFormPlain($plainPassword);

        $this->assertTrue($hashedPassword->equals($plainPassword));
    }

    public function testPasswordVerificationWithIncorrectPassword(): void
    {
        $plainPassword = 'testpassword123';
        $hashedPassword = HashedPassword::createFormPlain($plainPassword);

        $this->assertFalse($hashedPassword->equals('wrongpassword'));
    }

    public function testStringRepresentationIsHashed(): void
    {
        $plainPassword = 'testpassword123';
        $hashedPassword = HashedPassword::createFormPlain($plainPassword);

        $hashedString = (string) $hashedPassword;
        $this->assertNotEquals($plainPassword, $hashedString);
        $this->assertStringStartsWith('$2y$', $hashedString);
    }

    public function testPasswordHashingIsDeterministic(): void
    {
        $plainPassword = 'testpassword123';
        $hashedPassword1 = HashedPassword::createFormPlain($plainPassword);
        $hashedPassword2 = HashedPassword::createFormPlain($plainPassword);

        $this->assertNotEquals((string) $hashedPassword1, (string) $hashedPassword2);
        $this->assertTrue($hashedPassword1->equals($plainPassword));
        $this->assertTrue($hashedPassword2->equals($plainPassword));
    }
}