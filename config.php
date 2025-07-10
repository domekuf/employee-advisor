<?php
define("APP_ROUTES", "routes.php");
define("APP_ENTRY", "login");
function RT_generate() {
    $rt = "//".$_SERVER["SERVER_NAME"]."/";
    $req = explode("/", $_SERVER["REQUEST_URI"]);
    foreach ($req as $r) {
        if ($r == APP_ROUTES)
            break;
        if ($r == "")
            continue;
        $rt .= $r."/";
    }
    return $rt;
}
define("RT", RT_generate());
define("BS", RT."vendor/twbs/bootstrap/dist/");
define("JQ", RT."vendor/components/jquery/");
define("FA", RT."vendor/components/font-awesome/");
define("DB", "database/db");

