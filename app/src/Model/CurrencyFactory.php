<?php

namespace App\Model;

use App\Components\Container;

class CurrencyFactory extends Model
{
    public function getCurrencies(array $filter = []):array
    {
        $result = [];

        $currencies = $this->db->select('Currency')->fetchAll();

        foreach($currencies as $currency){

            if(!empty($filter['hide_currencies']) && in_array($currency['id'], $filter['hide_currencies']))
                continue;

            $result[] = new Currency($currency['id'], $currency['code'], $currency['name']);
        }

        return $result;
    }

    public function getCurrency($code)
    {
        $currency = $this->db->select('Currency', [
            'code' => $code
        ])->fetch();

        return new Currency($currency['id'], $currency['code'], $currency['name']);
    }
}