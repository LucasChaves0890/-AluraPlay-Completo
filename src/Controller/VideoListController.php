<?php

declare(strict_types=1);

namespace Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository as RepositoryVideoRepository;

class VideoListController implements RequestHandlerInterface
{
    public function __construct(
        private RepositoryVideoRepository $videoRepository,
        private Engine $template
    ) 
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $videoList = $this->videoRepository->all();

        return new Response(200, body: $this->template->render(
            'video-list',
            ['videoList' => $videoList]
        ));
    }
}
