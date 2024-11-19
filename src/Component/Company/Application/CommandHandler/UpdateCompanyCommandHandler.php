<?php

declare(strict_types=1);

namespace App\Component\Company\Application\CommandHandler;

use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Company\Application\Command\UpdateCompanyCommand;
use App\Component\Company\Application\Exception\CompanyNotFoundException;
use App\Component\Company\Domain\Presenter\CompanyPresenterInterface;
use App\Component\Company\Domain\Storage\CompanyStorageInterface;
use Symfony\Component\Clock\ClockInterface;

final readonly class UpdateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyPresenterInterface $companyPresenter,
        private CompanyStorageInterface $companyStorage,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws CompanyNotFoundException
     */
    public function __invoke(UpdateCompanyCommand $command): void
    {
        $company = $this->companyPresenter->findById($command->uuid);

        if (!$company) {
            throw new CompanyNotFoundException();
        }

        if (!$command->name && !$command->address) {
            return;
        }

        if (null !== $command->name) {
            $company->changeName($command->name);
        }

        if (null !== $command->address) {
            $company->changeAddress($command->address);
        }

        $company->setUpdatedAt($this->clock->now());

        $this->companyStorage->store($company);
    }
}
