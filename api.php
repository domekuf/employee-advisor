<?php
require __DIR__ . '/vendor/autoload.php';
include ('config-server.php');

$api = new Slim\App();

$api->get('/hello/{name}', function ($request, $response, $args) {
    return $response->getBody()->write("Hello, " . $args['name']);
});

$api->get("/reviews/{employee}", function ($request, $response, $args) {
    $db = new PDO("sqlite:database/db"); 
    $res = $db->query("select * from reviews where employee = ".$args['employee']);
    $array = [];
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $array [] = $row;
    }
    return $response->withJson($array);
});

$api->get("/employees", function ($request, $response, $args) {
    $db = new PDO("sqlite:database/db"); 
    $res = $db->query("select * from employees");
    $array = [];
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $array [] = $row;
    }
    return $response->withJson($array);
});

$api->get("/users", function ($request, $response, $args) {
    $db = new PDO("sqlite:database/db"); 
    $res = $db->query("select * from users");
    $array = [];
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $array [] = $row;
    }
    return $response->withJson($array);
});

$api->run();
