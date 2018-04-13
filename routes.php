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
    <body>
<?php
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
    $res = $db->query("select * from employees");
    $body = '
        <h1>Review an employee</h1>
        <form method="POST" action="submit">
            <select name="employee-id" class="form-control">';

    while ($row = $res->fetch(\PDO::FETCH_ASSOC)){
         $body .= '
                <option value="'.$row["id"].'">'.$row["name"].'</option>';
    }
    $body .= '
            </select>
            <div class="form-group">
                <label for="review-text">Write your comment here:</label>
                <textarea name="review-text" class="form-control" id="review-text" rows="3"></textarea>
            </div>
            <div class="form-group">
                <input type="hidden" name="review-rate" />
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>';
    return $response->getBody()->write($body);
});

$routes->any('/submit', function ($request, $response, $args) {
    $body = '
    <h1>Congratulations</h1>
    <p>You\'ve successfully reviewed '.$request->getParsedBody()["employee-id"].'</p>';
    return $response->getBody()->write($body);
});

$routes->run();
?>
        <script src="<?=JQ?>jquery.min.js"></script>
        <script src="<?=BS?>js/bootstrap.bundle.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
