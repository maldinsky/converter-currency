<?php

use App\App;
use App\Components\Container;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();
$container = new \App\Container();
$response = $container->get(App::class)->handler($request);
$response->send();
