<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\HistoryVisitor;
use App\Model\Visitor;
use Symfony\Component\HttpFoundation\Response;

class HistoryController
{

    private $template_render;
    private $history;

    public function __construct(TemplateRender $template_render, HistoryVisitor $history)
    {
        $this->template_render = $template_render;
        $this->history = $history;
    }

    public function index()
    {
        $history = $this->history->getHistory();

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