<?php
namespace App\Application\Buses;

use App\Application\Buses\QueryBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

class QueryBus implements QueryBusInterface {
    public function __construct(private Dispatcher $dispatcher) {}

    public function ask(object $query): mixed {
        return $this->dispatcher->dispatch($query);
    }
}
