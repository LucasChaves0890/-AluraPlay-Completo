<?php

namespace Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository;

class VideoFormController implements RequestHandlerInterface
{
    public function __construct(
        private VideoRepository $repository,
        private Engine $template
    ) 
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        $video = null;
        if ($id !== false && $id !== null) {
            $video = $this->repository->find($id);
        }

        return new Response(200, body: $this->template->render(
            'video-form',
            ['video' => $video]
        ));
    }
}
