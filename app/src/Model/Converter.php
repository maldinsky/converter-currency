<?php

namespace App\Model;

use GuzzleHttp\Client;

class Converter extends Model
{
    private $to;
    private $from;
    private $amount;
    private $result;

    public function __construct(string $to, string $from, $amount)
    {
        parent::__construct();
        $this->to = $to;
        $this->from = $from;
        $this->amount = $amount;
        $method = 'http://apilayer.net/api/live?access_key=9eeee3614b908e92a8651f54246c439e&currencies=USD,';
        $method .= $to;
        $method .= ',' . $from;
        try {
            $client = new Client();
            $response = $client->request(
                'GET',
                $method
            );

            $request_result = json_decode($response->getBody(), true);

            if ($request_result['success']) {
                if (
                    !empty($request_result['quotes']['USD' . $to])
                    && !empty($request_result['quotes']['USD' . $from])
                    && !empty($request_result['quotes']['USDUSD'])
                ) {
                    $this->result = ($amount * $request_result['quotes']['USD' . $to]) / $request_result['quotes']['USD' . $from];
                }
            } else {
                $this->result = 'Пока к сожалению конвертация валют невозможна. Попробуйте позже';
            }
        } catch (\Exception $e) {
            $this->result = 'Пока к сожалению конвертация валют невозможна. Попробуйте позже';
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getCurrencyTo()
    {
        return $this->to;
    }

    public function getCurrencyFrom()
    {
        return $this->from;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
