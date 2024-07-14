<?php

declare(strict_types=1);

namespace App\Common\Domain;

abstract class AggregateRoot
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    protected function record(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return EventInterface[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
