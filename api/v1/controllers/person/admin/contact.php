<?php

use Model\Entity\Contact;

Route::get("all", function () {
    App::$response =  Func::pagi("p.*", function ($statement) {
        $req = Contact::db()->select($statement)->from("contact p");

        $date_range = Func::dateRange(App::$request);
        $req =  $req->where("(p.creation_date >= ? and p.creation_date <= ?) ", [$date_range[0], $date_range[1]]);

        if (@App::$request["q"]) {
            $q = App::$request["q"];
            $req =  $req->where(
                "(concat(IFNULL(p.full_name))  LIKE concat('%',?,'%') or IFNULL(p.email,'')  LIKE concat('%',?,'%') or 
                IFNULL(p.phone,'')  LIKE concat('%',?,'%') or 
                IFNULL(p.subject,'')  LIKE concat('%',?,'%') or 
                IFNULL(p.message,'')  LIKE concat('%',?,'%') )",
                [$q, $q, $q, $q, $q]
            );
        }
        if (@App::$request["status"]) {
            $req =  $req->where(
                "status=?",
                [App::$request["status"]]
            );
        }
        return $req;
    });
});


Route::post('', function () {
    App::$request["shop_id"] = SHOP_ID;
    if (@App::$request["id"]) {
        Contact::update(App::$request, "id=?", [@App::$request["id"]]);
    } else {
        App::$request["creation_date"] = strtotime("now");
        Contact::insert(App::$request);
    }
});


Route::delete('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    Contact::delete("id=?", [$id]);
});
