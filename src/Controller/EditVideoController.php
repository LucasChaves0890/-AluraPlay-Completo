<?php

namespace Controller;

use Entity\Video;
use Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\VideoRepository;

class EditVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        if ($id === false && null) {
            $this->addErrorMessage('Id inválida');
            return new Response(400, [
                'Location' => '/'
            ]);
        }

        $queryParams = $request->getParsedBody();
        $url = filter_var($queryParams['url'], FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('Url inválida');
            return new Response(400, [
                'Location' => '/'
            ]);
        }

        $title =  filter_var($queryParams['title']);
        if ($title === false) {
            $this->addErrorMessage('Titulo inválido');
            return new Response(400, [
                'Location' => '/'
            ]);
        }
        $video = new Video($url, $title);
        $video->setId($id);

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                __DIR__ . '/../../public/img/uploads/' . $_FILES['image']['name']
            );
            $video->setFilePath($_FILES['image']['name']);
        }

        $sucess = $this->videoRepository->update($video);

        if ($sucess === false) {
            $this->addErrorMessage('Erro ao Editar o video');
            return new Response(302, [
                'Location' => '/'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/'
            ]);
        };
    }
}
