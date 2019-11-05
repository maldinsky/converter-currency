<?php

namespace App\Controller;

use App\Components\TemplateRender;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundController implements RequestHandlerInterface
{
    private $templateRender;

    public function __construct(TemplateRender $templateRender)
    {
        $this->templateRender = $templateRender;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $content = $this->templateRender->render('not_found');

        return new HtmlResponse($content);
    }
}
