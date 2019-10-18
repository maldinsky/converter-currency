<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\Converter;
use App\Model\CurrencyMapper;
use App\Model\HistoryVisitor;
use App\Model\VisitorMapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    private $template_render;
    private $visitor;
    private $currency_mapper;
    private $history;

    public function __construct(TemplateRender $template_render, CurrencyMapper $currency_mapper, VisitorMapper $visitor_mapper, HistoryVisitor $history)
    {
        $this->template_render = $template_render;
        $this->currency_mapper = $currency_mapper;
        $this->visitor = $visitor_mapper->getVisitor();
        $this->history = $history;
    }

    public function index()
    {
        $visitor_setting = $this->visitor->getSetting();

        $filter = [];

        if(!empty($visitor_setting['hide_currencies'])){
            $filter = [
                'hide_currencies' => $visitor_setting['hide_currencies']
            ];
        }

        $currencies = $this->currency_mapper->getCurrencies($filter);

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

        $this->history->addHistory($converter);

        return new JsonResponse(
            $result
        );
    }
}