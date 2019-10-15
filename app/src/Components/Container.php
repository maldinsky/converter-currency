<?php

namespace App\Components;

class Container
{
    private static $container = [];

    public static function get(string $name)
    {
        if (!array_key_exists($name, self::$container)) {
            throw new \Exception('Сервис не найден - "' . $name . '"');
        }
        return self::$container[$name];
    }

    public static function set(string $name, $value)
    {
        self::$container[$name] = $value;
    }

}