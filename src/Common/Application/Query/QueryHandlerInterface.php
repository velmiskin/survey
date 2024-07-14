<?php

namespace App\Common\Application\Query;

interface QueryHandlerInterface
{
    public function __invoke(QueryInterface $query): void;
}