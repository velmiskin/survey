<?php

declare(strict_types=1);


namespace App\Common\Application\Bus;

use App\Common\Domain\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;

    /**
     * @param EventInterface[] $events
     */
    public function dispatchMany(array $events): void;
}
