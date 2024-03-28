<?php

namespace Controller;

use Entity\Video;
use finfo;
use Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository;

class CreateVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getParsedBody();

        $url = filter_var($queryParams['url'], FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('Url inválida');
            return new Response(400, [
                'Location' => '/novo-video'
            ]);
        }

        $title =  filter_input(INPUT_POST, 'title');
        if ($title === '') {
            $this->addErrorMessage('Titulo não informado');
            return new Response(400, [
                'Location' => '/novo-video'
            ]);
        }

        $video = new Video($url, $title);
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $safeFilePath = uniqid('upload_') . '_' . pathinfo($_FILES['image']['name']);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);

            if (str_starts_with($mimeType, 'image/')) {
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    __DIR__ . '/../../public/img/uploads/' . $safeFilePath
                );
                $video->setFilePath($safeFilePath);
            }
        }

        $sucess = $this->videoRepository->add($video);
        
        if ($sucess === false) {
            $this->addErrorMessage('Falha ao cadastrar video');

            return new Response(302, [
                'Location' => '/novo-video'
            ]);
        }else {
            return new Response(302, [
                'Location' => '/'
            ]);
        }
            
        
    }
}
