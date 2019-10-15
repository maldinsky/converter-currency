<?php

namespace App\Components;

use App\Controller\HistoryController;
use App\Controller\MainController;
use App\Controller\SettingController;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

class Router
{
    private $route_collection;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->route_collection = new RouteCollection();

        $this->route_collection->add('home', new Route('/', ['_controller' => [MainController::class, 'index']]));
        $this->route_collection->add('settings', new Route('/settings', ['_controller' => [SettingController::class, 'index']]));
        $this->route_collection->add('settings_save', new Route('/settings/save', ['_controller' => [SettingController::class, 'saveSetting']]));
        $this->route_collection->add('history', new Route('/history', ['_controller' => [HistoryController::class, 'index']]));
        $this->route_collection->add('converter', new Route('/converter', ['_controller' => [MainController::class, 'converter']]));
    }

    public function match(Request $request)
    {
        $context = new RequestContext();

        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->route_collection, $context);

        $request->attributes->add($matcher->match($request->getPathInfo()));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

//         $controllerResolver->getController($request)
        $controller = $this->container->get($request->attributes->get('_controller')[0]);

        return [
            'controller' => $controller,
        ];
    }
}
