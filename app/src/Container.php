<?php

declare(strict_types=1);

namespace App;

use Cekta\DI\Provider\Autowiring;
use Cekta\DI\Provider\KeyValue;

class Container extends \Cekta\DI\Container
{
    public function __construct()
    {
        $providers[] = KeyValue::closureToService(require __DIR__ . '/../service.php');
        $providers[] = new Autowiring();
        parent::__construct(...$providers);
    }
}
