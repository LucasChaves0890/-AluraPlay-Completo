<?php

namespace Controller;

use Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Repository\UserRepository;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private UserRepository $repository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //$queryParams = $request->getParsedBody();

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        $userData = $this->repository->getUser($email);
        $correctPassword = password_verify($password, $userData['password'] ?? '');

        if (!$correctPassword) {
            $this->addErrorMessage('Usuário ou senha inválidos');
            return new Response(302, [
                'Location' => '/login'
            ]);
        }

        if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) {
            $this->repository->updateHash($password, $userData['id']);
        }
       
        $_SESSION['logado'] = true;
        return new Response(302, [
            'Location' => '/'
        ]);
    }
}
