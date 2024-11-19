<?php

namespace App\Tests\Factory;

use App\Component\Company\Infrastructure\Doctrine\Entity\Company;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Company>
 */
final class CompanyFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Company::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'id' => Uuid::uuid4(),
            'name' => self::faker()->company(),
            'active' => self::faker()->boolean(),
            'address' => [
                'postcode' => self::faker()->postcode(),
                'city' => self::faker()->city(),
                'street' => self::faker()->streetAddress(),
                'houseNumber' => self::faker()->buildingNumber(),
            ],
            'createdAt' => self::faker()->dateTimeBetween('-1 year'),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }
}
