<?php

namespace Controller;

use Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository;

class DeleteVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {   
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        if ($id === null || $id === false) {
            
            $this->addErrorMessage('Id inválido');
            return new Response(302, [
                'Location' => '/'
            ]);
        }
        
        $sucess = $this->videoRepository->remove($id);
    
        if ($sucess === false) {
            $this->addErrorMessage('Falha ao Excluir video');
            return new Response(302, [
                'Location' => '/'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/'
            ]);
        }
    }
}
