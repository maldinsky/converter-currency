<?php

use App\Components\Router;
use App\Components\TemplateRender;
use Cekta\DI\Container;
use Cekta\DI\Provider\Autowiring;
use Cekta\DI\Provider\KeyValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$session = new Session();
$session->start();

try {
    $router = new Router();
    $action = $router->match($request);

    $providers[] = new KeyValue([
        TemplateRender::class => new TemplateRender(__DIR__ . '/../templates'),
        Request::class => $request,
        PDO::class => new PDO(
            "mysql:host=converter-mysql;port=3306;dbname=dbname",
            "dbuser",
            "dbpass",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]
        ),
        'codeVisitor' => $session->getId(),
    ]);

    $providers[] = new Autowiring();
    $container = new Container(...$providers);

    $response = $container->get($action['handler'])->{$action['method']}();
} catch (ResourceNotFoundException $e) {
    $response = new Response(
        'Страница не найдена',
        Response::HTTP_NOT_FOUND,
        ['content-type' => 'text/html']
    );
}

$response->send();