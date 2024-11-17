<?php

declare(strict_types=1);

namespace App\Common\Application\Bus;

use App\Common\Application\Query\QueryInterface;

interface QueryBusInterface
{
    public function handle(QueryInterface $query): void;
}
