<?php
namespace App\Application\Buses;

interface QueryBusInterfacea {
    public function ask(object $query): mixed;
}
