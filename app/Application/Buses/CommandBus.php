<?php
namespace App\Application\Buses;

use App\Application\Buses\CommandBusInterfacea;
use Illuminate\Contracts\Bus\Dispatcher;

class CommandBusa implements CommandBusInterfacea {
    public function __construct(private Dispatcher $dispatcher) {
    }

    public function send(object $command): mixed {
        return $this->dispatcher->dispatch($command);
    }
}
