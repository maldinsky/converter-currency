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
    private $templateRender;
    private $visitor;
    private $currencyMapper;
    private $visitorMapper;

    public function __construct(
        TemplateRender $templateRender,
        VisitorMapper $visitorMapper,
        CurrencyMapper $currencyMapper
    ) {
        $this->templateRender = $templateRender;
        $this->visitorMapper = $visitorMapper;
        $this->visitor = $visitorMapper->getVisitor();
        $this->currencyMapper = $currencyMapper;
    }

    public function index()
    {
        $currencies = $this->currencyMapper->getCurrencies();

        $visitor = $this->visitor;
        $settings_visitor = $visitor->getSetting();

        $hide_currencies = !empty($settings_visitor['hide_currencies']) ? $settings_visitor['hide_currencies'] : [];
        $history_limit = !empty($settings_visitor['history_limit']) ? $settings_visitor['history_limit'] : 20;

        $content = $this->templateRender->render('settings', [
            'history_limit' => $history_limit,
            'hide_currencies' => $hide_currencies,
            'currencies' => $currencies
        ]);

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function saveSetting()
    {
        $request = Request::createFromGlobals();

        $setting = [
            'hide_currencies' => !empty($request->get('hide_currencies')) ? $request->get('hide_currencies') : [],
            'history_limit' => !empty($request->get('history_limit')) ? $request->get('history_limit') : 20
        ];

        $this->visitorMapper->updateVisitorSetting($setting);

        return new JsonResponse();
    }
}
