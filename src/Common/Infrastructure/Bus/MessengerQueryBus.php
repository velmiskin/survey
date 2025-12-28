<?php

namespace App\Common\Infrastructure\Bus;

use App\Common\Application\Bus\QueryBusInterface;
use App\Common\Application\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }

}