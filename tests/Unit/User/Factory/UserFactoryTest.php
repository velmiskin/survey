<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Factory;

use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Enum\Role as RoleEnum;
use App\Component\User\Domain\Event\UserRegisteredEvent;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\Exception\NonUniqueEmailException;
use App\Component\User\Domain\Factory\UserFactory;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\ValueObject\HashedPassword;
use App\Component\User\Domain\ValueObject\Role;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserFactoryTest extends TestCase
{
    private UserFactory $userFactory;
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    protected function setUp(): void
    {
        $this->uniqueEmailSpecification = $this->createMock(UniqueEmailSpecificationInterface::class);
        $this->userFactory = new UserFactory($this->uniqueEmailSpecification);
    }

    public function testCreateUser(): void
    {
        $id = Uuid::uuid4();
        $email = 'test@example.com';
        $password = 'password123';
        $firstName = 'John';
        $lastName = 'Doe';
        $role = 'ROLE_PATIENT';
        $createdAt = new \DateTimeImmutable();

        $user = $this->userFactory->create(
            $id,
            $email,
            $password,
            $firstName,
            $lastName,
            $role,
            $createdAt
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($id, $user->getId());
        $this->assertEquals(new Email($email), $user->getEmail());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertTrue($user->getPassword()->equals($password));
        $this->assertEquals(new Role(RoleEnum::ROLE_PATIENT), $user->getRole());
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testCreateUserWithInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        $this->userFactory->create(
            Uuid::uuid4(),
            'invalid-email',
            'password123',
            'John',
            'Doe',
            'ROLE_PATIENT',
            new \DateTimeImmutable()
        );
    }

    public function testCreateUserWithInvalidPassword(): void
    {
        $this->expectException(InvalidPasswordException::class);

        $this->userFactory->create(
            Uuid::uuid4(),
            'test@example.com',
            'short',
            'John',
            'Doe',
            'ROLE_PATIENT',
            new \DateTimeImmutable()
        );
    }

    public function testRegisterUserWithUniqueEmail(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->with('test@example.com')
            ->willReturn(true);

        $id = Uuid::uuid4();
        $email = 'test@example.com';
        $password = 'password123';
        $firstName = 'John';
        $lastName = 'Doe';
        $role = 'ROLE_PATIENT';
        $createdAt = new \DateTimeImmutable();

        $user = $this->userFactory->register(
            $id,
            $email,
            $password,
            $firstName,
            $lastName,
            $role,
            $createdAt
        );

        $this->assertInstanceOf(User::class, $user);
        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);
    }

    public function testRegisterUserWithNonUniqueEmail(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->with('test@example.com')
            ->willReturn(false);

        $this->expectException(NonUniqueEmailException::class);

        $this->userFactory->register(
            Uuid::uuid4(),
            'test@example.com',
            'password123',
            'John',
            'Doe',
            'ROLE_PATIENT',
            new \DateTimeImmutable()
        );
    }

    public function testCreateUserWithAllRoles(): void
    {
        $roles = ['ROLE_ADMIN', 'ROLE_EMPLOYEE', 'ROLE_PATIENT'];

        foreach ($roles as $role) {
            $user = $this->userFactory->create(
                Uuid::uuid4(),
                'test@example.com',
                'password123',
                'John',
                'Doe',
                $role,
                new \DateTimeImmutable()
            );

            $this->assertEquals(new Role(RoleEnum::from($role)), $user->getRole());
        }
    }

    public function testCreateUserWithInvalidRole(): void
    {
        $this->expectException(\ValueError::class);

        $this->userFactory->create(
            Uuid::uuid4(),
            'test@example.com',
            'password123',
            'John',
            'Doe',
            'INVALID_ROLE',
            new \DateTimeImmutable()
        );
    }

    public function testRegisterUserCreatesCorrectEvent(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->willReturn(true);

        $id = Uuid::uuid4();
        $email = 'test@example.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $role = 'ROLE_ADMIN';
        $createdAt = new \DateTimeImmutable();

        $user = $this->userFactory->register(
            $id,
            $email,
            'password123',
            $firstName,
            $lastName,
            $role,
            $createdAt
        );

        $events = $user->pullEvents();
        /** @var UserRegisteredEvent $event */
        $event = $events[0];

        $this->assertEquals($id, $event->userId);
        $this->assertEquals(new Email($email), $event->email);
        $this->assertEquals($firstName, $event->firstName);
        $this->assertEquals($lastName, $event->lastName);
        $this->assertEquals(new Role(RoleEnum::ROLE_ADMIN), $event->role);
        $this->assertEquals($createdAt, $event->createdAt);
    }
}