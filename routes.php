<?php
require __DIR__ . '/vendor/autoload.php';
include ('config-server.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta name="HandheldFriendly" content="true" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Employee Advisor</title>
        <link rel="apple-touch-icon" sizes="57x57"         href="favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60"         href="favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72"         href="favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76"         href="favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114"       href="favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120"       href="favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144"       href="favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152"       href="favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180"       href="favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32"    href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96"    href="favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16"    href="favicon/favicon-16x16.png">
        <link rel="manifest"                               href="favicon/manifest.json">
        <meta name="msapplication-TileImage"            content="favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <meta name="msapplication-TileColor" content="#ffffff">
        <link rel="stylesheet" href="<?=BS?>css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=FA?>css/fontawesome-all.min.css">
    </head>
    <body><?php
$routes = new Slim\App();

$routes->any('/login', function ($request, $response, $args) {
    return $response->getBody()->write('
        <form method="POST" action="review">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="email" class="form-control" id="username" name="username" placeholder="user@company.com">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>');
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
        return $response->getBody()->write("/routes.php/login");
    }
    $res = $db->query("select * from employees");
    $body = '
        <link rel="stylesheet" href="/css/review.css">
        <h1>Review an employee</h1>
        <form method="POST" action="submit">
            <input type="hidden" name="user-id" value="'.$user_id.'">
            <div class="form-group">
                <label for="employee-id">Choose the employee to review:</label>
                <select id="employee-id" name="employee-id" class="form-control">';

    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
         $body .= '
                    <option value="'.$row["id"].'">'.$row["name"].'</option>';
    }
    $body .= '
                </select>
            </div>
            <div class="form-group">
                <label for="review-text">Write your comment here:</label>
                <textarea name="review-text" class="form-control" id="review-text" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Rate:</label>
                <input id="review-rate" type="hidden" name="review-rate" value="0"/>
                <div id="star-rate">
                    <i role="button" class="far fa-star"></i>
                    <i role="button" class="far fa-star"></i>
                    <i role="button" class="far fa-star"></i>
                    <i role="button" class="far fa-star"></i>
                    <i role="button" class="far fa-star"></i>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <script defer src="/js/review.js"></script>';
    return $response->getBody()->write($body);
});

$routes->any('/submit', function ($request, $response, $args) {
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
            $body = '
    <h1>Congratulations</h1>
    <p>You\'ve successfully reviewed '.$row["name"].'</p>
    <h2>Resume</h2>
    <p>'.urldecode($row["content"]).'</p>
    ';
            for ($i = 0; $i < $row["rate"]; $i ++) {
                $body .='
    <i class="fa fa-star"></i>';
            }
        }
    } else {
        $body = '
    <h1>Error</h1>
    <p>Something went wrong! Try again.</p>';
    }
    return $response->getBody()->write($body);
});

$routes->run();
?>
        <script src="<?=JQ?>jquery.min.js"></script>
        <script src="<?=BS?>js/bootstrap.bundle.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
