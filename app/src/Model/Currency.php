<?php

namespace App\Model;

class Currency extends Model
{
    protected $id;
    protected $code;
    protected $name;

    public function __construct(int $id, string $code, string $name)
    {
        parent::__construct();
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
