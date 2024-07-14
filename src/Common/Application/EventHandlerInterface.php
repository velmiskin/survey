<?php

declare(strict_types=1);

namespace App\Common\Application;

use App\Common\Domain\EventInterface;

interface EventHandlerInterface
{
    public function handle(EventInterface $event): void;
}
