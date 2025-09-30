<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Exception;

use App\Component\User\Domain\Exception\WrongOldPasswordException;
use PHPUnit\Framework\TestCase;

final class WrongOldPasswordExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new WrongOldPasswordException();

        $this->assertEquals('Old password is wrong', $exception->getMessage());
        $this->assertEquals(WrongOldPasswordException::MESSAGE, $exception->getMessage());
    }

    public function testExceptionType(): void
    {
        $exception = new WrongOldPasswordException();

        $this->assertInstanceOf(\DomainException::class, $exception);
        $this->assertInstanceOf(WrongOldPasswordException::class, $exception);
    }

    public function testExceptionConstant(): void
    {
        $this->assertEquals('Old password is wrong', WrongOldPasswordException::MESSAGE);
    }
}