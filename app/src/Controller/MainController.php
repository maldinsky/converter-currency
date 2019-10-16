<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\Converter;
use App\Model\CurrencyFactory;
use App\Model\HistoryVisitor;
use App\Model\Visitor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    private $template_render;
    private $visitor;

    public function __construct(TemplateRender $template_render, Visitor $visitor)
    {
        $this->visitor = $visitor;
        $this->template_render = $template_render;
    }

    public function index()
    {
        $currency_factory = new CurrencyFactory();
        $visitor_setting = $this->visitor->getSetting();

        $filter = [];

        if(!empty($visitor_setting['hide_currencies'])){
            $filter = [
                'hide_currencies' => $visitor_setting['hide_currencies']
            ];
        }

        $currencies = $currency_factory->getCurrencies($filter);

        $content = $this->template_render->render('main',
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

        $request = Request::createFromGlobals();;

        $converter = new Converter($request->get('to'), $request->get('from'), $request->get('amount'));
        $result = $converter->getResult();

        $history = new HistoryVisitor();
        $history->addHistory($this->visitor, $converter);

        return new JsonResponse(
            $result
        );
    }
}