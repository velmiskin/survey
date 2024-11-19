<?php

declare(strict_types=1);

namespace App\Component\Company\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Company\Application\Command\CreateCompanyCommand;
use App\Component\Company\Domain\Entity\Company;
use App\Component\Company\Domain\Storage\CompanyStorageInterface;
use Symfony\Component\Clock\ClockInterface;

final readonly class CreateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyStorageInterface $companyStorage,
        private ClockInterface $clock,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateCompanyCommand $command): void
    {
        $company = new Company(
            uuid: $command->uuid,
            name: $command->name,
            active: false,
            address: $command->address,
            createdAt: $this->clock->now(),
            updatedAt: $this->clock->now()
        );

        $this->companyStorage->store($company);
        $this->eventBus->dispatchMany($company->pullEvents());
    }
}
