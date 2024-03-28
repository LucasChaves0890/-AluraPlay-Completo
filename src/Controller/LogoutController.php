<?php

namespace Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $_SESSION['logado'] = false;
        unset($_SESSION['logado']); 
        return new Response(200, [
            'Location' => '/' 
        ]);
    }
}
