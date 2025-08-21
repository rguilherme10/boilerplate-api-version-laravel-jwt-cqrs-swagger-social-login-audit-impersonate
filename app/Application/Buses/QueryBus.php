<?php
namespace App\Application\Buses;

use App\Application\Buses\QueryBusInterfacea;
use Illuminate\Contracts\Bus\Dispatcher;

class QueryBusa implements QueryBusInterfacea {
    public function __construct(private Dispatcher $dispatcher) {}

    public function ask(object $query): mixed {
        return $this->dispatcher->dispatch($query);
    }
}
