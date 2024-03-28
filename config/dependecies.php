<?php

use DI\ContainerBuilder;
use League\Plates\Engine;
use PDO;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    PDO::class => function () {
        $dbPath = __DIR__ . '/../dataBase.sqlite';
        return new PDO("sqlite:$dbPath");
    },
    Engine::class => function () {
        $templatePath = __DIR__ . '/../views';
        return new Engine($templatePath);
    }
]);

/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();

return $container;
