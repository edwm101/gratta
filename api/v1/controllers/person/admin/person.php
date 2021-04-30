<?php

use Model\Entity\Person;

Route::get('', function () {
    App::$response["result"] = Person::db()->select("p.*")->from("person p")
        ->where("p.id=?", App::$request["id"])->execute()->fetchObject();
});


Route::get("all", function () {

    $max = App::$request["max"] ?? 12;
    $cp = App::$request["cp"] ?? 1;
    $sortby = App::$request["sortby"] ?? "creation_date";
    $sort_type = App::$request["sort_type"] ?? "desc";

    App::$response =  Func::pagi("p.*", function ($statement) {
        $req = Person::db()->select($statement)->from("person p");

        if (isset(App::$request["q"])) {
            $q = App::$request["q"];
            $req =  $req->where(
                "first_name LIKE concat('%',?,'%') || last_name LIKE concat('%',?,'%') || p.phone LIKE concat('%',?,'%') 
             || p.address LIKE concat('%',?,'%')  || provider.name LIKE concat('%',?,'%')",
                [$q, $q, $q, $q,  $q]
            );
        }
        if (isset(App::$request["role"])) {
            $req =  $req->where(
                "p.role=?",
                [App::$request["role"]]
            );
        }
        return $req;
    });
});

Route::post('', function () {

    App::$request["last_login"] = strtotime("now");

    if (isset(App::$request["id"])) {
        if (isset(App::$request["password"])) {
            App::$request["password"] = Func::fHash(App::$request["password"]);
        }
        Person::save(App::$request);
    } else {
        App::$request["password"] = Func::fHash(App::$request["password"]);
        App::$request["creation_date"] = strtotime("now");
        Person::insert(App::$request);
    }
});

Route::delete('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    Person::delete("id=?", $id);
});
