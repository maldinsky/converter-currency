<?php

namespace App\Model;

class Visitor
{
    private $id;
    private $code;
    private $setting;

    public function __construct(int $id, string $code, string $setting = '')
    {
        $this->id = $id;
        $this->code = $code;
        $this->setting = json_decode($setting, true);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getSetting()
    {
        return $this->setting;
    }
}
