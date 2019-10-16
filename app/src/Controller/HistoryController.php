<?php

namespace App\Controller;

use App\Components\Container;
use App\Components\TemplateRender;
use App\Model\CurrencyFactory;
use App\Model\HistoryVisitor;
use App\Model\Visitor;
use Symfony\Component\HttpFoundation\Response;

class HistoryController
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
        $history = (new HistoryVisitor())->getHistory($this->visitor);

        $content = $this->template_render->render('history',
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