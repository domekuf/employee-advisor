<?php
require __DIR__ . '/vendor/autoload.php';

$api = new Slim\App();

$api->get('/hello/{name}', function ($request, $response, $args) {
    return $response->getBody()->write("Hello, " . $args['name']);
});

$api->get('/dummy', function ($request, $response, $args) {
    $db = new Nette\Database\Connection("sqlite3:ea", null, null);
    return $response->getBody()->write("Dummy");
});

$api->run();
