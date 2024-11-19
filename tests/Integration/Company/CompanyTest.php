<?php

declare(strict_types=1);

namespace App\Tests\Integration\Company;

use App\Common\Domain\ValueObject\Address;
use App\Component\Company\Application\Command\ActivateCompanyCommand;
use App\Component\Company\Application\Command\CreateCompanyCommand;
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
        $address = new Address('22222', 'City', 'Street', '1');

        $command = new CreateCompanyCommand($uuid, $name, $address);
        $this->commandBus->dispatch($command);

        $this->bus('command.bus')->dispatched()->assertCount(1);
        $dispatched = $this->bus('command.bus')->dispatched()->all();
        $this->assertInstanceOf(CreateCompanyCommand::class, $dispatched[0]->getMessage());
    }

    public function testActivateCompanyCommand(): void
    {
        $company = CompanyFactory::createOne(['active' => false]);

        $command = new ActivateCompanyCommand($company->getId());
        $this->commandBus->dispatch($command);

        $this->bus('command.bus')->dispatched()->assertCount(1);
        $this->bus('event.bus')->dispatched()->assertCount(1);
        $updatedCompany = $this->entityManager->find(Company::class, $company->getId());

        self::assertTrue($updatedCompany->isActive());
    }
}
