<?php

Route::get('resume', function () {
    $date_range = Func::dateRange(App::$request);

    $req = ShQuery::db()->select("count(p.id) as total,
    sum(CASE WHEN amount is not null THEN 1 ELSE 0 END) as  winner, 
    sum(IFNULL(amount,0)) as amount")
        ->from("draw_code p")->where("((select creation_date from draw where p.draw_id=id limit 1) >= ? and (select creation_date from draw where p.draw_id=id limit 1) <= ?)", [$date_range[0], $date_range[1]]);;

    $req = $req->execute()->fetchObject();

    App::$response["result"] = $req;
});
