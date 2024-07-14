<?php

declare(strict_types=1);

namespace App\Common\Application\Bus;

use App\Common\Application\Command\CommandInterface;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): void;
}
