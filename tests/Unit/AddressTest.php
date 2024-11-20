<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Common\Domain\ValueObject\Address;
use PHPUnit\Framework\TestCase;

final class AddressTest extends TestCase
{
    public function testAddressCreated(): void
    {
        $address = new Address('22222', 'City', 'Street', '1');
        $this->assertEquals('22222', $address->postcode);
        $this->assertEquals('City', $address->city);
        $this->assertEquals('Street', $address->street);
        $this->assertEquals('1', $address->houseNumber);
    }

    public function testAddressToArray(): void
    {
        $address = new Address('22222', 'City', 'Street', '1');
        $this->assertEquals([
            'postcode' => '22222',
            'city' => 'City',
            'street' => 'Street',
            'houseNumber' => '1',
        ], $address->toArray());
    }

    public function testAddressToString(): void
    {
        $address = new Address('22222', 'City', 'Street', '1');
        $this->assertEquals('Street 1, 22222 City', (string) $address);
    }

    public function testAddressCreateFromArray(): void
    {
        $address = Address::createFromArray([
            'postcode' => '22222',
            'city' => 'City',
            'street' => 'Street',
            'houseNumber' => '1',
        ]);
        $this->assertEquals('22222', $address->postcode);
        $this->assertEquals('City', $address->city);
        $this->assertEquals('Street', $address->street);
        $this->assertEquals('1', $address->houseNumber);
    }

    /*public function testAddressCreateFromArrayWithMissingData(): void
    {
        $this->expectException(\TypeError::class);
        Address::createFromArray([
            'postcode' => '22222',
            'city' => 'City',
            'street' => 'Street',
        ]);
    }*/
}
