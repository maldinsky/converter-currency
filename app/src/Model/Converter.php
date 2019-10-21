<?php

namespace App\Model;

use GuzzleHttp\Client;

class Converter
{
    private $to;
    private $from;
    private $amount;
    private $result;
    private $apiKeyConverter;

    public function __construct(string $apiKeyConverter, string $to, string $from, $amount)
    {
        $this->to = $to;
        $this->from = $from;
        $this->amount = $amount;
        $this->apiKeyConverter = $apiKeyConverter;

        try {
            $client = new Client();
            $response = $client->request('GET', 'http://apilayer.net/api/live?access_key=' . $this->apiKeyConverter .'&currencies=USD,' . $to . ',' . $from);

            $requestResult = json_decode($response->getBody(), true);

            if ($requestResult['success']) {
                if (
                    !empty($requestResult['quotes']['USD' . $to])
                    && !empty($requestResult['quotes']['USD' . $from]) 
                    && !empty($requestResult['quotes']['USDUSD'])
                ) {
                    $this->result = ($amount * $requestResult['quotes']['USD' . $to]) / $requestResult['quotes']['USD' . $from];
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