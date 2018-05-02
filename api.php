<?php
require __DIR__ . '/vendor/autoload.php';
include ('config.php');

$db = new PDO("sqlite:".DB);
$api = new Slim\App();

$api->get("/reviews/{user_id}", function ($request, $response, $args) {
    global $db;
    $res = $db->query("select * from reviews where user = ".$args['user_id']);
    $array = [];
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $array [] = $row;
    }
    return $response->withJson($array);
});

$api->get("/reviews", function ($request, $response, $args) {
    global $db;
    $res = $db->query("select * from reviews");
    $array = [];
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $array [] = $row;
    }
    return $response->withJson($array);
});

$api->run();
