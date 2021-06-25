<?php

use Model\Entity\Governorate;
use Model\Entity\Location;

Route::get("governorate", function () {

    App::$response['result'] =  Governorate::findAll();
});


Route::get("all", function () {

    App::$response['result'] =  Location::findAll();
});
