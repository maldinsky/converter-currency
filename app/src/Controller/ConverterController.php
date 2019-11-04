<?php

namespace App\Controller;

use App\Model\Converter;
use App\Model\HistoryVisitor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ConverterController implements RequestHandlerInterface
{
    private $history;
    private $apiKeyConverter;

    public function __construct(HistoryVisitor $history, string $apiKeyConverter)
    {
        $this->history = $history;
        $this->apiKeyConverter = $apiKeyConverter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $converterRequest = $request->getParsedBody();

        $converter = new Converter(
            $this->apiKeyConverter,
            $converterRequest['to'],
            $converterRequest['from'],
            $converterRequest['amount']
        );

        $result = $converter->getResult();

        $this->history->addHistory($converter);

        return new JsonResponse($result);
    }
}
