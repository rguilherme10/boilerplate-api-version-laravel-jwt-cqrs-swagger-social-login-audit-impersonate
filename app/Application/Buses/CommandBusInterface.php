<?php
namespace App\Application\Buses;

interface CommandBusInterface {
    public function send(object $command): mixed;
}
