<?php

namespace Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Error404Controller implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $requst): ResponseInterface
    {
        return new Response(400);
    }
}
