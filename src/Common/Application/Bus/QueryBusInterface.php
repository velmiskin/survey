<?php

namespace App\Common\Application\Bus;

use App\Common\Application\Query\QueryInterface;

interface QueryBusInterface
{
    public function handle(QueryInterface $query): void;
}
