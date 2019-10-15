<?php

use App\Components\Container;
use App\Components\Router;
use App\Components\TemplateRender;
use App\Model\VisitorFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/../vendor/autoload.php';

// request
$request = Request::createFromGlobals();

// user
$session = new Session();
$session->start();

// action
try
{
    $router = new Router();
    $action = $router->match($request);

    // Создаем или проверяем существование пользователя
    $visitor_factory = new VisitorFactory();
    $visitor = $visitor_factory->getVisitor($session->getId());

    // Формирование контейнера
    Container::set('request', $request);
    Container::set('router', $router);
    Container::set('template_render', new TemplateRender(__DIR__ . '/../templates'));
    Container::set('visitor', $visitor);

    $response = call_user_func_array($action['controller'], $action['arguments']);
}
catch (ResourceNotFoundException $e)
{
    $response = new Response(
        'Страница не найдена',
        Response::HTTP_NOT_FOUND,
        ['content-type' => 'text/html']
    );
}

$response->send();