<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\CurrencyFactory;
use App\Model\Visitor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController
{
    private $template_render;
    private $visitor;

    public function __construct(TemplateRender $template_render, Visitor $visitor)
    {
        $this->template_render = $template_render;
        $this->visitor = $visitor;
    }

    public function index()
    {
        $currency_factory = new CurrencyFactory();
        $currencies = $currency_factory->getCurrencies();

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

        $this->visitor->updateSetting($setting);

        return new JsonResponse();
    }
}