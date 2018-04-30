<?php
require __DIR__ . '/vendor/autoload.php';
include ('config-server.php');
define ('RT', '/routes.php');
use Slim\Views\PhpRenderer;
use Slim\Flash\Messages;

$db = new PDO("sqlite:".DB);
$routes = new Slim\App();
$container = $routes->getContainer();
$container['renderer'] = new PhpRenderer("./view");
$container['flash'] = function () {
    return new Messages();
};

session_start();

function url($str) {
    return RT.$str;
}

function flash($t) {
    $messages = $t->flash->getMessages();
    if ($messages) {
        $flash["title"] = $messages["error"][0]["title"];
        $flash["content"] = $messages["error"][0]["content"];
    }
    return $flash;
}

function cookieGet($request, $cookieName) {
    $cookieName = urldecode($cookieName);
    return urldecode($request->getCookieParam($cookieName));
}

function cookieSet(&$response, $cookieName, $cookieValue) {
    $expirationMinutes = 10;
    $expiry = new \DateTimeImmutable('now + '.$expirationMinutes.'minutes');
    $cookie = urlencode($cookieName).'='.
    urlencode($cookieValue).'; expires='.$expiry->format(\DateTime::COOKIE).'; Max-Age=' .
    $expirationMinutes * 60 . '; path=/;';
    $response = $response->withAddedHeader('Set-Cookie', $cookie);
}

function isAuthenticated(&$user_id, &$username) {
    global $db, $container;
    $request = $container->request;
    $username = trim($request->getParsedBody()["username"]);
    if (!isset($username) || $username == "") {
        $username = cookieGet($request, "username");
    }
    $res = $db->query("select * from users where email like '$username'");
    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
        $user_id = $row["id"];
    }
    return isset($user_id);
}

function authenticate(&$response, $username) {
    cookieSet($response, "username", $username);
}

$routes->any('/login', function ($request, $response, $args) {
    $args["flash"] = flash($this);
    return $this->renderer->render($response, '/login.php', $args);
})->setName("login");

$routes->any('/review', function ($request, $response, $args) {
    global $db, $container;
    if (!isAuthenticated($user_id, $username)) {
        $this->flash->addMessage("error", [
            "title" => "Login failed",
            "content" => "User $username not found. Psst, try with user@company.com"
        ]);
        return $response->withRedirect($this->router->pathFor('login'));
    }
    authenticate($response, $username);
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
    global $db;
    if (!isAuthenticated($user_id, $username)) {
        $this->flash->addMessage("error", [
            "title" => "Authentication failed",
            "content" => "Please login again."
        ]);
        return $response->withRedirect($this->router->pathFor('login'));
    }
    $b = $request->getParsedBody();
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
    global $db;
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
    global $db;
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
    global $db;
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
