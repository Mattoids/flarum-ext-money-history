<?php

namespace Mattoid\MoneyHistory\Middleware;

use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MoneyMiddleware implements MiddlewareInterface
{
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $typesAllowed = [];
        $actor = RequestUtil::getActor($request);
        $userId = Arr::get($actor, 'id');
        $money = Arr::get($actor, 'money');

        $response = $handler->handle($request);

        if (!in_array($request->getMethod(), $typesAllowed)) {
            return $response;
        }



        return $response;
    }
}
