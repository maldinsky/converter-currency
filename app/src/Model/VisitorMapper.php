<?php

namespace App\Model;

use App\Components\Db;

class VisitorMapper
{
    private $db;
    private $code;

    public function __construct(Db $db, string $codeVisitor)
    {
        $this->db = $db;
        $this->code = $codeVisitor;
    }

    public function getVisitor(): Visitor
    {
        $result = $this->db->select('Visitor', [
            'session_code' => $this->code
        ])->fetch();

        if (!$result) {
            $id = $this->db->insert('Visitor', [
                'session_code' => $this->code,
                'setting' => ''
            ]);

            return new Visitor($id, $this->code);
        }

        return new Visitor($result['id'], $this->code, $result['setting']);
    }

    public function updateVisitorSetting(array $setting)
    {
        $visitor = $this->getVisitor();

        $this->db->update('Visitor', [
            'setting' => json_encode($setting)
        ], [
            'id' => $visitor->getId()
        ]);
    }
}