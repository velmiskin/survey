<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

final readonly class Address implements \Stringable
{
    public function __construct(
        public string $postcode,
        public string $city,
        public string $street,
        public string $houseNumber,
    ) {
    }

    public function __toString(): string
    {
        return $this->street.' '.$this->houseNumber.', '.$this->postcode.' '.$this->city;
    }

    /**
     * @param array<string, string> $data
     */
    public static function createFromArray(array $data): self
    {
        return new self(
            postcode: $data['postcode'],
            city: $data['city'],
            street: $data['street'],
            houseNumber: $data['houseNumber'],
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'postcode' => $this->postcode,
            'city' => $this->city,
            'street' => $this->street,
            'houseNumber' => $this->houseNumber,
        ];
    }
}
