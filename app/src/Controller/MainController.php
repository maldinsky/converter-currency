<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\CurrencyMapper;
use App\Model\VisitorMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class MainController implements RequestHandlerInterface
{
    private $templateRender;
    private $visitor;
    private $currencyMapper;

    public function __construct(
        TemplateRender $templateRender,
        CurrencyMapper $currencyMapper,
        VisitorMapper $visitorMapper
    ) {
        $this->templateRender = $templateRender;
        $this->currencyMapper = $currencyMapper;
        $this->visitor = $visitorMapper->getVisitor();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $visitorSetting = $this->visitor->getSetting();

        $filter = [];
        if (!empty($visitorSetting['hide_currencies'])) {
            $filter = [
                'hide_currencies' => $visitorSetting['hide_currencies']
            ];
        }

        $currencies = $this->currencyMapper->getCurrencies($filter);

        $content = $this->templateRender->render('main', [
            'currencies' => $currencies,
        ]);

        return new HtmlResponse($content);
    }
}
