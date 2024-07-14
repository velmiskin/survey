<?php

declare(strict_types=1);

namespace App\Component\User\Domain\ValueObject;

use App\Component\User\Domain\Enum\Role as RoleEnum;

final readonly class Role implements \Stringable
{
    public function __construct(private RoleEnum $role)
    {
    }

    public function __toString(): string
    {
        return $this->role->value;
    }

    public function isEquals(Role $role): bool
    {
        return $this->role->value === (string) $role;
    }

    public function isAdmin(): bool
    {
        return RoleEnum::ROLE_ADMIN === $this->role;
    }

    public function isEmployee(): bool
    {
        return RoleEnum::ROLE_EMPLOYEE === $this->role;
    }

    public function isPatient(): bool
    {
        return RoleEnum::ROLE_PATIENT === $this->role;
    }
}
