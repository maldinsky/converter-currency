<?php

namespace App\Controller;

use App\Components\Container;
use App\Model\Converter;
use App\Model\CurrencyFactory;
use App\Model\HistoryVisitor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    public function index()
    {
        $currency_factory = new CurrencyFactory();
        $visitor_setting = Container::get('visitor')->getSetting();

        $filter = [];

        if(!empty($visitor_setting['hide_currencies'])){
            $filter = [
                'hide_currencies' => $visitor_setting['hide_currencies']
            ];
        }

        $currencies = $currency_factory->getCurrencies($filter);

        $content = Container::get('template_render')->render('main',
            [
                'currencies' => $currencies,
            ]
        );

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function converter(){

        $request = Container::get('request');

        $converter = new Converter($request->get('to'), $request->get('from'), $request->get('amount'));
        $result = $converter->getResult();

        $history = new HistoryVisitor();
        $history->addHistory(Container::get('visitor'), $converter);

        return new JsonResponse(
            $result
        );
    }
}