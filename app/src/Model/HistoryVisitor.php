<?php

namespace App\Model;

class HistoryVisitor extends Model
{
    public function addHistory(Visitor $visitor, Converter $converter)
    {
        $this->db->insert('History', [
            'id_visitor' => $visitor->getId(),
            'currency_to' => $converter->getCurrencyTo(),
            'currency_from' => $converter->getCurrencyFrom(),
            'amount' => $converter->getAmount(),
            'result' => $converter->getResult()
        ]);
    }

    public function getHistory(Visitor $visitor)
    {

        $setting = $visitor->getSetting();

        $limit = (!empty($setting['history_limit'])) ? (int)$setting['history_limit'] : 20;

        $history = $this->db->select('History', [
            'id_visitor' => $visitor->getId()
        ])->fetchAll();

        return $history;
    }
}
