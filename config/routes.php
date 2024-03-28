<?php

use Controller\CreateVideoController;
use Controller\DeleteVideoController;
use Controller\DeleteVideoCover;
use Controller\EditVideoController;
use Controller\JsonVideoListController;
use Controller\LoginController;
use Controller\LoginFormController;
use Controller\LogoutController;
use Controller\NewJsonVideoController;
use Controller\VideoFormController;
use Controller\VideoListController;

return [
    'GET|/' => VideoListController::class,
    'GET|/novo-video' => VideoFormController::class,
    'POST|/novo-video' => CreateVideoController::class,
    'GET|/editar-video' => VideoFormController::class,
    'POST|/editar-video' => EditVideoController::class,
    'GET|/remover-video' => DeleteVideoController::class,
    'GET|/login' => LoginFormController::class,
    'POST|/login' => LoginController::class,
    'GET|/logout' => LogoutController::class,
    'GET|/remover-imagem' => DeleteVideoCover::class,
    'GET|/videos-json' => JsonVideoListController::class,
    'POST|/video' => NewJsonVideoController::class,
];
