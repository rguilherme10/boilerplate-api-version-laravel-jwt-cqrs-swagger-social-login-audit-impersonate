<?php
namespace App\Application\Buses;

use App\Application\Buses\CommandBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

class CommandBus implements CommandBusInterface {
    public function __construct(private Dispatcher $dispatcher) {}

    public function send(object $command): mixed {
        return $this->dispatcher->dispatch($command);
    }
}
