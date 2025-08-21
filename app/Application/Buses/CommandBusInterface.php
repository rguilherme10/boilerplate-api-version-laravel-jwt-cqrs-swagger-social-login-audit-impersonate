<?php
namespace App\Application\Buses;

interface CommandBusInterfacea {
    public function send(object $command): mixed;
}
