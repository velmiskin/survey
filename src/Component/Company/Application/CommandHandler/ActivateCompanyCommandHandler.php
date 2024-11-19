<?php

declare(strict_types=1);

namespace App\Component\Company\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\Company\Application\Command\ActivateCompanyCommand;
use App\Component\Company\Application\Exception\CompanyNotFoundException;
use App\Component\Company\Domain\Presenter\CompanyPresenterInterface;
use App\Component\Company\Domain\Storage\CompanyStorageInterface;

final readonly class ActivateCompanyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CompanyPresenterInterface $companyPresenter,
        private CompanyStorageInterface $companyStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    /**
     * @throws CompanyNotFoundException
     */
    public function __invoke(ActivateCompanyCommand $command): void
    {
        $company = $this->companyPresenter->findById($command->uuid);

        if (!$company) {
            throw new CompanyNotFoundException();
        }

        $company->activate();

        $this->companyStorage->store($company);
        $this->eventBus->dispatchMany($company->pullEvents());
    }
}
