<?php
Route::mount("/contact", function () {
    include_once("contact.php");
});


Route::mount("/location", function () {
    include_once("location.php");
});

Route::mount("/draw", function () {
    include_once("draw.php");
});
