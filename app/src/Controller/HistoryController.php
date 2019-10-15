<?php

namespace App\Controller;

use App\Components\Container;
use App\Model\CurrencyFactory;
use App\Model\HistoryVisitor;
use Symfony\Component\HttpFoundation\Response;

class HistoryController
{
    public function index()
    {
        $history = (new HistoryVisitor())->getHistory(Container::get('visitor'));

        $content = Container::get('template_render')->render('history',
            [
                'history' => $history,
            ]
        );

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}