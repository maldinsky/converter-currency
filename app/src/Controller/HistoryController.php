<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\HistoryVisitor;
use Symfony\Component\HttpFoundation\Response;

class HistoryController
{
    private $templateRender;
    private $history;

    public function __construct(TemplateRender $templateRender, HistoryVisitor $history)
    {
        $this->templateRender = $templateRender;
        $this->history = $history;
    }

    public function index()
    {
        $history = $this->history->getHistory();

        $content = $this->templateRender->render('history', [
            'history' => $history,
        ]);

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}
