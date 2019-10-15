<?php
declare(strict_types=1);

use App\Components\TemplateRender;
use Psr\Container\ContainerInterface;

return [
    PDO::class => function (ContainerInterface $c) {
        return new PDO(
            "mysql:host=converter-mysql;port=3306;dbname=dbname",
            "dbuser",
            "dbpass",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]
        );
    },
    TemplateRender::class => function (ContainerInterface $c) {
        return new TemplateRender(__DIR__ . '/../templates');
    },
    ContainerInterface::class => function(ContainerInterface $c) {
        return $c;
    }
];
