<?php

namespace App\Model;

class VisitorFactory extends Model
{
    public function getVisitor($code):Visitor
    {
        $result = $this->db->select('Visitor', [
            'session_code' => $code
        ])->fetch();

        if(!$result){
            $id = $this->db->insert('Visitor', [
                'session_code' => $code,
                'setting' => ''
            ]);

            return new Visitor($id, $code);
        }

        return new Visitor($result['id'], $code, $result['setting']);
    }
}