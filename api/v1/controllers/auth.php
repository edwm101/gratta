<?php

use Model\Entity\Person;

Route::get('validation', function () {
    $user = Person::find("id=?", TOKEN_ID, "id,username,first_name,last_name,image,email,phone,email,role,team_id");
    App::$response['result'] = [
        "info" =>  $user,
        "token" =>  Auth::createToken($user)
    ];
});


Route::post('signin', function () {
    $username    = @App::$request["username"];
    $password = @App::$request["password"];
    Func::emptyCheck([$username, $password]);
    $username = strtolower($username);

    if (!$user = Person::find("username=?", $username, "id,username,first_name,last_name,email,image,phone,email,role,password,team_id")) {
        Func::error("USER_NOT_EXISTS", 401);
    }
    if (!password_verify($password, $user->password)) {
        Func::error("INVALID_PASSWORD", 401);
    }
    unset($user->password);


    App::$response['result'] = [
        "info" => $user,
        "token" =>  Auth::createToken($user)
    ];
});
