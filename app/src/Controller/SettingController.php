<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\CurrencyMapper;
use App\Model\VisitorMapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController
{
    private $template_render;
    private $visitor;
    private $currency_mapper;
    private $visitor_mapper;

    public function __construct(TemplateRender $template_render, VisitorMapper $visitor_mapper, CurrencyMapper $currency_mapper)
    {
        $this->template_render = $template_render;
        $this->visitor_mapper = $visitor_mapper;
        $this->visitor = $visitor_mapper->getVisitor();
        $this->currency_mapper = $currency_mapper;
    }

    public function index()
    {
        $currencies = $this->currency_mapper->getCurrencies();

        $visitor = $this->visitor;
        $settings_visitor = $visitor->getSetting();

        $hide_currencies = !empty($settings_visitor['hide_currencies'])? $settings_visitor['hide_currencies']: [];
        $history_limit = !empty($settings_visitor['history_limit'])? $settings_visitor['history_limit']: 20;

        $content = $this->template_render->render('settings',
            [
                'history_limit' => $history_limit,
                'hide_currencies' => $hide_currencies,
                'currencies' => $currencies
            ]
        );

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function saveSetting(){
        $request = Request::createFromGlobals();

        $setting = [
            'hide_currencies' => !empty($request->get('hide_currencies'))? $request->get('hide_currencies'): [],
            'history_limit' => !empty($request->get('history_limit'))? $request->get('history_limit'): 20
        ];

        $this->visitor_mapper->updateVisitorSetting($setting);

        return new JsonResponse();
    }
}