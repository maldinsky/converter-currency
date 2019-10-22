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
    private $templateRender;
    private $visitor;
    private $currencyMapper;
    private $history;
    private $apiKeyConverter;

    public function __construct(
        TemplateRender $templateRender,
        CurrencyMapper $currencyMapper,
        VisitorMapper $visitorMapper,
        HistoryVisitor $history,
        string $apiKeyConverter
    ) {
        $this->templateRender = $templateRender;
        $this->currencyMapper = $currencyMapper;
        $this->visitor = $visitorMapper->getVisitor();
        $this->history = $history;
        $this->apiKeyConverter = $apiKeyConverter;
    }

    public function index()
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

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function converter()
    {
        $request = Request::createFromGlobals();

        $converter = new Converter(
            $this->apiKeyConverter,
            $request->get('to'),
            $request->get('from'),
            $request->get('amount')
        );

        $result = $converter->getResult();

        $this->history->addHistory($converter);

        return new JsonResponse(
            $result
        );
    }
}
