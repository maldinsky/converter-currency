<?php

namespace App\Model;

use App\Components\Db;

class HistoryVisitor
{
    private $db;
    private $visitor;

    public function __construct(Db $db, VisitorMapper $visitorMapper)
    {
        $this->db = $db;
        $this->visitor = $visitorMapper->getVisitor();
    }

    public function addHistory(Converter $converter)
    {
        $this->db->insert('History', [
            'id_visitor' => $this->visitor->getId(),
            'currency_to' => $converter->getCurrencyTo(),
            'currency_from' => $converter->getCurrencyFrom(),
            'amount' => $converter->getAmount(),
            'result' => $converter->getResult()
        ]);
    }

    public function getHistory()
    {
        $setting = $this->visitor->getSetting();

        $limit = (!empty($setting['history_limit'])) ? (int)$setting['history_limit'] : 20;

        $history = $this->db->select('History', [
            'id_visitor' => $this->visitor->getId()
        ])->fetchAll();

        return $history;
    }
}
