<?php
require_once './classes/Route.php';
require_once './classes/Func.php';
require_once './classes/Auth.php';
require_once './classes/ShQuery.php';
require_once "./classes/sasa/autoload.php";

$db = new database\DB(DB_DSN, DB_USER, DB_PASS);

class App 
{
    public static  $request = [], $response = [];

    public static function init()
    {
        self::$request = self::initRequest();
    }


    public static function db()
    {
        global $db;
        return $db;
    }


    public static function initRequest()
    {
        if (!$_post   = json_decode(file_get_contents("php://input"))) {
            $_post    = json_decode(json_encode($_POST), false);
        }
        $_get         = json_decode(json_encode($_GET), FALSE);
        return   array_merge((array) $_get, (array) $_post);
    }

    public static function request($param = null)
    {
        if ($param != null) {
            return self::$request[$param];
        } else {
            return self::$request;
        }
    }

    public static function response($items = [])
    {
        foreach ($items as $key => $item) {
            self::$response[$key] = $item;
        }
    }

    
}
