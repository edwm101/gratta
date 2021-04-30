<?php

define("APP_START_TIME", microtime(true));
require_once 'core.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods:  GET,POST,PUT,DELETE,PATCH");

//REQUIRES
require_once 'vendor/autoload.php';
require_once './classes/App.php';

App::init();
Auth::init();


if (DO_TRANSACTION) {
    App::db()->beginTransaction();
}

/**** ROUTES *****/
Route::before('OPTIONS', "{*}", function () {  // Verifies all the headers sent by the server in response
    exit;
});



$routes = json_decode(file_get_contents('./routes.json'), true);


//Recaptch 
Route::before('GET|POST|PUT|DELETE|PATCH', $routes["recaptcha"], function () {
    $recaptchaToken = @App::$request["recaptcha_token"];
    $recaptchaInfo =  json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_KEY . "&response=" . $recaptchaToken), false);
    if (!$recaptchaInfo->success)
        Func::error("INVALID_RECAPTCHA", 401);
});



// Before Router Middleware 
foreach ($routes["auth"] as $key => $item) {

    if (!array_key_exists("methods", $item)) {
        $_methods = 'GET|POST|PUT|DELETE|PATCH';
    } else {
        $_methods =  $item["methods"];
    }

    Route::before($_methods, $item["paths"], function ()  use ($item) {
        $token_key = $item["id_token_key"] ?? ID_TOKEN_KEY;

        if ($token = @Func::getHeader($token_key)) {
            Auth::setToken($token);
            $result = Auth::tryToDecode();


            if (!Auth::isAuth()) {  //To see if the token is valid
                Func::error("INVALID_TOKEN", 401, $result);
            }

            if (!in_array(@Auth::authData()->role, $item["roles"])) {  //To see if the current auth have the permission
                Func::error("INVALID_PERMISSION ", 401, "Only " . implode(" and ", $item["roles"]) . " can access this content");
            }

            if (@Auth::authData()->id) {
                foreach (Auth::authData() as $key => $token_key) {
                    define(strtoupper("TOKEN_" . $key), $token_key);
                }
            }
            // if (defined("TOKEN_EMAIL")) {
            //     if (TOKEN_EMAIL == 'demo@demo.com' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
            //         Func::error("IS_DEMO", 401);
            //     }
            // }

        } else {
            Func::error("API_KEY_NOT_EXISIT", 401, "You must use an API key to authenticate each request to our Platform APIs.");  //API TOKEN IS NULL
        }
    });
}



// Custom mount Handler -- (Controllers)
foreach ($routes["mounts"] as $key => $item) {
    Route::mount($item["path"], function () use ($item) {
        include_once('./controllers/' . $item["controller"] . '.php');
    });
}
/**** ROUTES *****/

// Custom 404 Handler
Route::set404(function () {
    Func::error("PAGE_NOT_FOUND", 404, "404 Not Found");
});

//Thunderbirds are go!
Route::run(function () {
    http_response_code(200);
    App::response(["runtime" => number_format(microtime(true) - APP_START_TIME, 5), "status" => "OK"]);
    // ob_start("ob_gzhandler");
    echo json_encode(App::$response);
    // ob_end_flush();
});

if (DO_TRANSACTION) {
    App::db()->commit();
}
