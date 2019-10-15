<?php

declare(strict_types=1);

namespace App;

use App\Components\Router;
use Symfony\Component\HttpFoundation\Request;

class App
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handler(Request $request)
    {
        $action = $this->router->match($request);
        return call_user_func_array($action['controller']);
    }
}
