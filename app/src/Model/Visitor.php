<?php

namespace App\Model;

class Visitor extends Model
{
    private $id;
    private $code;
    private $setting;

    public function __construct(int $id, string $code, string $setting = '')
    {
        parent::__construct();
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

    public function updateSetting(array $setting)
    {
        $this->setting = $setting;

        $this->db->update('Visitor', [
            'setting' => json_encode($setting)
        ], [
            'id' => $this->getId()
        ]);
    }
}