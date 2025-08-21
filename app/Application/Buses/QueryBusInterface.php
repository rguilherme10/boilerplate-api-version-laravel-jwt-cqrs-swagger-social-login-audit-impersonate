<?php
namespace App\Application\Buses;

interface QueryBusInterface {
    public function ask(object $query): mixed;
}
