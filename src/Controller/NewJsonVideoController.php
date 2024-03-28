<?php

namespace Controller;

use Entity\Video;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository;

class NewJsonVideoController implements RequestHandlerInterface
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request = $request->getBody()->getContents();
        $videoData = json_decode($request, true);
        $video = new Video($videoData['url'], $videoData['title']);
        $this->videoRepository->add($video);   
        
        return new Response(201);
    }
}
