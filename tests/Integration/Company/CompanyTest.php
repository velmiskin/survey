<?php

declare(strict_types=1);

namespace App\Tests\Integration\Company;

use App\Common\Domain\ValueObject\Address;
use App\Component\Company\Application\Command\ActivateCompanyCommand;
use App\Component\Company\Application\Command\CreateCompanyCommand;
use App\Component\Company\Application\Command\UpdateCompanyCommand;
use App\Component\Company\Application\Exception\CompanyNotFoundException;
use App\Component\Company\Infrastructure\Doctrine\Entity\Company;
use App\Tests\Factory\CompanyFactory;
use App\Tests\Integration\AbstractTestCase;
use Ramsey\Uuid\Uuid;

final class CompanyTest extends AbstractTestCase
{
    public function testCreateCompanyCommand(): void
    {
        $uuid = Uuid::uuid4();
        $name = 'Company name';
        $address = $this->createAddress();

        $command = new CreateCompanyCommand($uuid, $name, $address);
        $this->commandBus->dispatch($command);

        $dispatchedCommands = $this->bus('command.bus')->dispatched();
        $dispatchedCommands->assertCount(1);
        $this->assertInstanceOf(CreateCompanyCommand::class, $dispatchedCommands->first()->getMessage());

        $company = $this->entityManager->find(Company::class, $uuid);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($uuid, $company->getId());
        $this->assertEquals($name, $company->getName());
        $this->assertFalse($company->isActive());
    }

    public function testActivateCompanyCommand(): void
    {
        $company = CompanyFactory::createOne(['active' => false]);

        $command = new ActivateCompanyCommand($company->getId());
        $this->commandBus->dispatch($command);

        $dispatchedCommands = $this->bus('command.bus')->dispatched();
        $dispatchedEvents = $this->bus('event.bus')->dispatched();

        $dispatchedCommands->assertCount(1);
        $dispatchedEvents->assertCount(1);

        $this->assertInstanceOf(ActivateCompanyCommand::class, $dispatchedCommands->first()->getMessage());

        $updatedCompany = $this->entityManager->find(Company::class, $company->getId());
        self::assertTrue($updatedCompany->isActive());
    }

    public function testChangeCompanyCommand(): void
    {
        $company = CompanyFactory::createOne();

        $newName = 'New company name';
        $newAddress = $this->createAddress();

        $command = new UpdateCompanyCommand($company->getId(), $newName, $newAddress);

        $this->commandBus->dispatch($command);

        $dispatchedCommands = $this->bus('command.bus')->dispatched();

        $dispatchedCommands->assertCount(1);
        $this->assertInstanceOf(UpdateCompanyCommand::class, $dispatchedCommands->first()->getMessage());

        $updatedCompany = $this->entityManager->find(Company::class, $company->getId());

        $this->assertEquals($newName, $updatedCompany->getName());
        $this->assertSame($newAddress->toArray(), $updatedCompany->getAddress());
    }

    public function testActivateCompanyCommandCompanyNotFound(): void
    {
        $command = new ActivateCompanyCommand(Uuid::uuid4());

        $this->expectExceptionMessage(CompanyNotFoundException::MESSAGE);
        $this->commandBus->dispatch($command);
    }

    public function testChangeCompanyCommandCompanyNotFound(): void
    {
        $newAddress = $this->createAddress();
        $command = new UpdateCompanyCommand(Uuid::uuid4(), 'SomeName', $newAddress);

        $this->expectExceptionMessage(CompanyNotFoundException::MESSAGE);
        $this->commandBus->dispatch($command);
    }

    private function createAddress(): Address
    {
        return new Address('22222', 'City', 'Street', '1');
    }
}
