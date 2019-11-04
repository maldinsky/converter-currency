<?php

namespace App\Controller;

use App\Components\TemplateRender;
use App\Model\HistoryVisitor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HistoryController implements RequestHandlerInterface
{
    private $templateRender;
    private $history;

    public function __construct(TemplateRender $templateRender, HistoryVisitor $history)
    {
        $this->templateRender = $templateRender;
        $this->history = $history;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $history = $this->history->getHistory();

        $content = $this->templateRender->render('history', [
            'history' => $history,
        ]);

        return new HtmlResponse($content);
    }
}
