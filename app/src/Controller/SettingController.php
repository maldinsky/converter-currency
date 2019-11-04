<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\CurrencyMapper;
use App\Model\VisitorMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class SettingController implements RequestHandlerInterface
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

    public function handle(ServerRequestInterface $request): ResponseInterface
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

        return new HtmlResponse($content);
    }
}
