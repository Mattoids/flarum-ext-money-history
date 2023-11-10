<?php

namespace Mattoid\CheckinHistory\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MoneyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        app("log")->info($request->getUri());
        app("log")->info($request->getParsedBody());
        $response = $handler->handle($request);

        app("log")->info($response->getBody());

        return $response;
    }
}
