<?php

use App\Components\Router;
use App\Components\TemplateRender;
use Cekta\DI\Container;
use Cekta\DI\Provider\Autowiring;
use Cekta\DI\Provider\KeyValue;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Session\Session;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::create(__DIR__ . '/../');
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD']);

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$session = new Session();
$session->start();

$router = new Router();
$action = $router->match($request);

$providers[] = new KeyValue([
    TemplateRender::class => new TemplateRender(__DIR__ . '/../templates'),
    PDO::class => new PDO(
        'mysql:host='. getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ]
    ),
    'codeVisitor' => $session->getId(),
    'apiKeyConverter' => getenv('API_KEY_CONVERTER')
]);

$providers[] = new Autowiring();
$container = new Container(...$providers);

$response = $container->get($action['handler'])->handle($request);

$sapiEmitter = new SapiEmitter();
$sapiEmitter->emit($response);

