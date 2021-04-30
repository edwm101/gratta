<?php
Route::mount("/contact", function () {
    include_once("contact.php");
});

Route::mount("/draw", function () {
    include_once("draw.php");
});


Route::mount("/analytics", function () {
    include_once("analytics.php");
});



Route::mount("/file", function () {
    include_once("file.php");
});



Route::mount("/person", function () {
    include_once("person.php");
});
