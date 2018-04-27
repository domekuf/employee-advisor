<?php
require __DIR__ . '/vendor/autoload.php';
include ('config-server.php');
define ('RT', '/routes.php');
use Slim\Views\PhpRenderer;

$routes = new Slim\App();
$routes->getContainer()['renderer'] = new PhpRenderer("./view");

function url($str) {
    return RT.$str;
}

$routes->any('/login', function ($request, $response, $args) {
    return $this->renderer->render($response, '/login.php', $args);
});

$routes->any('/review', function ($request, $response, $args) {
    $db = new PDO("sqlite:database/db");
    $username = trim($request->getParsedBody()["username"]);
    if (!isset($username) || $username == "") {
        $username = $request->getCookieParam("username");
    }
    $res = $db->query("select * from users where email like '$username'");
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        //Assert !isset($user_id)
        $user_id = $row["id"];
    }
    if (!isset($user_id)) {
        return $response->withRedirect(url('/login'));
    }
    $args["user_id"] = $user_id;
    $employees = $db->query("select * from employees");
    while ($row = $employees->fetch(\PDO::FETCH_ASSOC)) {
            $args["employees"][] = [
                    "id" => $row["id"],
                    "name" => $row["name"]
            ];
    }
    return $this->renderer->render($response, '/review.php', $args);
});

$routes->post('/submit', function ($request, $response, $args) {
    $b = $request->getParsedBody();
    $db = new PDO("sqlite:database/db");
    $res = $db->query("select count(*) c from reviews where user = ".$b["user-id"]." and employee = ".$b["employee-id"]);
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $count_before = $row["c"];
    }
    $res = $db->query("insert
        into reviews (user, employee, content, rate)
        values (".$b["user-id"].", ".$b["employee-id"].", '".urlencode($b["review-text"])."', ".$b["review-rate"].")");
    $res = $db->query("select count(*) c from reviews where user = ".$b["user-id"]." and employee = ".$b["employee-id"]);
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $count_after = $row["c"];
    }
    if ($count_before + 1 == $count_after) {
        $res = $db->query("select * from reviews r join employees e on r.employee = e.id where r.id = (select max(id) from reviews)");
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
            $args["name"] = $row["name"];
            $args["rate"] = $row["rate"];
            $args["content"] = urldecode($row["content"]);
            return $this->renderer->render($response, '/submit.php', $args);
        }
    } else {
        return $this->renderer->render($response, '/error.php', $args);
    }
});

$routes->get('/review-history/{user_id}', function($request, $response, $args) {
    $db = new PDO("sqlite:database/db");
    $res = $db->query("select * from reviews where user=".$args["user_id"]);
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
        $args["reviews"][] = [
            "content" => urldecode($row["content"]),
            "rate" => $row["rate"]
        ];
    }
    return $this->renderer->render($response, '/review-history.php', $args);
});

$routes->get('/employees', function($request, $response, $args) {
    $db = new PDO("sqlite:database/db");
    $employees = $db->query("select * from employees");
    while ($row = $employees->fetch(\PDO::FETCH_ASSOC)) {
            $args["employees"][] = [
                    "link" => url('/employees/'.$row["id"]),
                    "name" => $row["name"]
            ];
    }

    return $this->renderer->render($response, '/employees.php', $args);
});

$routes->get('/employees/{employee_id}', function($request, $response, $args) {
    $db = new PDO("sqlite:database/db");
    $res = $db->query("select name from employees where id=".$args["employee_id"]);
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
        $args["name"] = $row["name"];
        $res = $db->query("select * from reviews where employee=".$args["employee_id"]);
        while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
            $args["reviews"][] = [
                "content" => urldecode($row["content"]),
                "rate" => $row["rate"]
            ];
        }
        return $this->renderer->render($response, '/employees%{employee_id}.php', $args);
    }
    return $this->renderer->render($response, '/error.php', $args);
});

$routes->run();
