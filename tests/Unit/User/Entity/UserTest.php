<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Entity;

use App\Common\Domain\ValueObject\Email;
use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Enum\Role as RoleEnum;
use App\Component\User\Domain\Event\UserPasswordChangedEvent;
use App\Component\User\Domain\Event\UserRegisteredEvent;
use App\Component\User\Domain\Exception\NonUniqueEmailException;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\ValueObject\HashedPassword;
use App\Component\User\Domain\ValueObject\Role;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends TestCase
{
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    protected function setUp(): void
    {
        $this->uniqueEmailSpecification = $this->createMock(UniqueEmailSpecificationInterface::class);
    }

    public function testUserCreation(): void
    {
        $id = Uuid::uuid4();
        $email = new Email('test@example.com');
        $firstName = 'John';
        $lastName = 'Doe';
        $password = HashedPassword::createFormPlain('password123');
        $role = new Role(RoleEnum::ROLE_PATIENT);
        $createdAt = new \DateTimeImmutable();

        $user = new User(
            $id,
            $email,
            $firstName,
            $lastName,
            $password,
            $role,
            $createdAt,
            $this->uniqueEmailSpecification
        );

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($role, $user->getRole());
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testUserRegistrationWithUniqueEmail(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->with('test@example.com')
            ->willReturn(true);

        $user = $this->createUser();
        $user->register();

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);
    }

    public function testUserRegistrationWithNonUniqueEmail(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->with('test@example.com')
            ->willReturn(false);

        $user = $this->createUser();

        $this->expectException(NonUniqueEmailException::class);
        $user->register();
    }

    public function testChangePassword(): void
    {
        $user = $this->createUser();
        $oldPassword = $user->getPassword();
        $newPassword = HashedPassword::createFormPlain('newpassword123');

        $user->changePassword($newPassword);

        $this->assertEquals($newPassword, $user->getPassword());
        $this->assertNotEquals($oldPassword, $user->getPassword());

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserPasswordChangedEvent::class, $events[0]);
    }

    public function testChangeFirstName(): void
    {
        $user = $this->createUser();
        $newFirstName = 'Jane';

        $user->changeFirstName($newFirstName);

        $this->assertEquals($newFirstName, $user->getFirstName());
    }

    public function testChangeLastName(): void
    {
        $user = $this->createUser();
        $newLastName = 'Smith';

        $user->changeLastName($newLastName);

        $this->assertEquals($newLastName, $user->getLastName());
    }

    public function testUserRegisteredEventContainsCorrectData(): void
    {
        $this->uniqueEmailSpecification
            ->expects($this->once())
            ->method('isUnique')
            ->willReturn(true);

        $user = $this->createUser();
        $user->register();

        $events = $user->pullEvents();
        /** @var UserRegisteredEvent $event */
        $event = $events[0];

        $this->assertEquals($user->getId(), $event->userId);
        $this->assertEquals($user->getEmail(), $event->email);
        $this->assertEquals($user->getFirstName(), $event->firstName);
        $this->assertEquals($user->getLastName(), $event->lastName);
        $this->assertEquals($user->getRole(), $event->role);
        $this->assertEquals($user->getCreatedAt(), $event->createdAt);
    }

    public function testUserPasswordChangedEventContainsCorrectData(): void
    {
        $user = $this->createUser();
        $newPassword = HashedPassword::createFormPlain('newpassword123');

        $user->changePassword($newPassword);

        $events = $user->pullEvents();
        /** @var UserPasswordChangedEvent $event */
        $event = $events[0];

        $this->assertEquals($user->getId(), $event->userId);
    }

    private function createUser(): User
    {
        return new User(
            Uuid::uuid4(),
            new Email('test@example.com'),
            'John',
            'Doe',
            HashedPassword::createFormPlain('password123'),
            new Role(RoleEnum::ROLE_PATIENT),
            new \DateTimeImmutable(),
            $this->uniqueEmailSpecification
        );
    }
}