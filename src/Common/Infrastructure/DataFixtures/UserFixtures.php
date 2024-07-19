<?php

namespace App\Common\Infrastructure\DataFixtures;

use App\Component\User\Infrastructure\Doctrine\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setId(UUid::fromString('f1b5f3b3-7f7b-4b8b-8b1e-3e1f0f3f3f3f'))
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('example@example.com')
            ->setPassword('12345678')
            ->setRole('ROLE_PATIENT')
            ->setCreatedAt(new DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}
