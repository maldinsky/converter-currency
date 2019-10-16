<?php

use App\Components\Router;
use App\Components\TemplateRender;
use App\Model\Visitor;
use App\Model\VisitorFactory;
use Cekta\DI\Container;
use Cekta\DI\Provider\Autowiring;
use Cekta\DI\Provider\KeyValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors',1);
ini_set('error_reporting',2047);

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
    $providers[] = new KeyValue([
        TemplateRender::class => new TemplateRender(__DIR__ . '/../templates'),
        Visitor::class => $visitor,
        Request::class => $request
    ]);

    $providers[] = new Autowiring();
    $container = new Container(...$providers);

    $response = $container->get($action['handler'])->{$action['method']}();
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