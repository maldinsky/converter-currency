<?php

namespace App\Model;

use App\Components\Db;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }
}