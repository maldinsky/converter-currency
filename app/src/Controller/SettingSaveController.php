<?php

namespace App\Controller;

use App\Model\VisitorMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class SettingSaveController implements RequestHandlerInterface
{
    private $visitorMapper;

    public function __construct(VisitorMapper $visitorMapper)
    {
        $this->visitorMapper = $visitorMapper;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $settingRequest = $request->getParsedBody();

        $setting = [
            'hide_currencies' => !empty($settingRequest['hide_currencies']) ? $settingRequest['hide_currencies'] : [],
            'history_limit' => !empty($settingRequest['history_limit']) ? $settingRequest['history_limit'] : 20
        ];

        $this->visitorMapper->updateVisitorSetting($setting);

        return new JsonResponse('Изменения сохранены!');
    }
}
