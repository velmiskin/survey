<?php

namespace App\Tests\Factory;

use App\Component\User\Domain\Enum\Role;
use App\Component\User\Infrastructure\Doctrine\Entity\User;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

final class UserFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'id' => Uuid::uuid4(),
            'email' => self::faker()->email(),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'password' => self::faker()->password(8),
            'role' => self::faker()->randomElement(Role::cases())->value,
            'createdAt' => self::faker()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
