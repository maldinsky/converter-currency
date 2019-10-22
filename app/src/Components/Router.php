<?php

namespace App\Components;

use App\Controller\HistoryController;
use App\Controller\MainController;
use App\Controller\SettingController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    private $routeCollection;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection();

        $this->routeCollection->add(
            'home',
            new Route('/', ['_controller' => [MainController::class, 'index']])
        );
        $this->routeCollection->add(
            'settings',
            new Route('/settings', ['_controller' => [SettingController::class, 'index']])
        );
        $this->routeCollection->add(
            'settings_save',
            new Route('/settings/save', ['_controller' => [SettingController::class, 'saveSetting']])
        );
        $this->routeCollection->add(
            'history',
            new Route('/history', ['_controller' => [HistoryController::class, 'index']])
        );
        $this->routeCollection->add(
            'converter',
            new Route('/converter', ['_controller' => [MainController::class, 'converter']])
        );
    }

    public function match(Request $request): array
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routeCollection, $context);

        $handler = $matcher->match($request->getPathInfo());

        return [
            'handler' => $handler['_controller'][0],
            'method' => $handler['_controller'][1]
        ];
    }
}
