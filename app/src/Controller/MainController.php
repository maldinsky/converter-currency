<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\Converter;
use App\Model\CurrencyFactory;
use App\Model\HistoryVisitor;
use App\Model\VisitorFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class MainController
{
    /**
     * @var TemplateRender
     */
    private $render;

    public function __construct(TemplateRender $render)
    {
        $this->render = $render;
    }

    public function index()
    {
        $currency_factory = new CurrencyFactory();
        $session = new Session();
        $session->start();
        $visitor_factory = new VisitorFactory();
        $visitor = $visitor_factory->getVisitor($session->getId());
        $visitor_setting = $visitor->getSetting();

        $filter = [];

        if (!empty($visitor_setting['hide_currencies'])) {
            $filter = [
                'hide_currencies' => $visitor_setting['hide_currencies']
            ];
        }

        $currencies = $currency_factory->getCurrencies($filter);

        $content = $this->render->render(
            'main',
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

    public function converter()
    {

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
