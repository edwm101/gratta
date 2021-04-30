<?php

use Model\Entity\File;

Route::post("", function () {
    $type = App::$request["type"] ?? "image";
    $path = @App::$request["path"];
    $name = @App::$request["name"];

    Func::emptyCheck([$path]);

    $name = Func::randomString(15);
    $data = Func::upload($path, $name);

    if ($data["upload"]) {
        $data["date"] = strtotime("now");
        $data["type"] = $type;
        File::save(array_merge(App::$request, $data));
    } else {
        Func::error("UPLOAD_ERROR", 500, $data["upload_error"]);
    }
});


Route::get('', function () {
    App::$response["result"] = File::find("p.id=?", App::$request["id"]);
});


Route::get("all", function () {
    App::$response = Func::pagi("*,concat('" . BASE_URL . "',path) as path", function ($statement) {
        $req = File::db()->select($statement)->from("file p");
        return $req;
    });
});


Route::delete('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    $data = File::find("id=?", $id);
    if ($data) {
        unlink($data->path);
        File::delete("id=?", $id);
    }
});
