<?php

namespace App\Model;

use App\Components\Db;

class CurrencyMapper
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getCurrencies(array $filter = []): array
    {
        $result = [];

        $currencies = $this->db->select('Currency')->fetchAll();

        foreach ($currencies as $currency) {
            if(!empty($filter['hide_currencies']) && in_array($currency['code'], $filter['hide_currencies']))
                continue;

            $result[] = new Currency($currency['code'], $currency['name']);
        }

        return $result;
    }

    public function getCurrency($code)
    {
        $currency = $this->db->select('Currency', [
            'code' => $code
        ])->fetch();

        return new Currency($currency['code'], $currency['name']);
    }
}
