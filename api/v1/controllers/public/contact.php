<?php

use Model\Entity\Contact;

Route::post('', function () {
    unset(App::$request["user_id"]);
    App::$request["creation_date"] = strtotime("now");
    App::$request["last_update"] = strtotime("now");
    Contact::insert(App::$request);
});
