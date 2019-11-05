<?php

namespace App\Components;

use App\Controller\ConverterController;
use App\Controller\HistoryController;
use App\Controller\MainController;
use App\Controller\NotFoundController;
use App\Controller\SettingController;
use App\Controller\SettingSaveController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Zend\Diactoros\ServerRequest;
use function FastRoute\simpleDispatcher;

class Router
{
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = simpleDispatcher(function(RouteCollector $r) {
            $r->addRoute('GET', '/', MainController::class);
            $r->addRoute('GET', '/settings', SettingController::class);
            $r->addRoute('GET', '/history', HistoryController::class);
            $r->addRoute('POST', '/converter', ConverterController::class);
            $r->addRoute('POST', '/settings/save', SettingSaveController::class);
        });
    }

    public function match(ServerRequest $request): array
    {
        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $handler = NotFoundController::class;
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                break;
        }

        return [
            'handler' => $handler
        ];
    }
}

