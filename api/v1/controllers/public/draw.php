<?php

use Model\Entity\Draw;
use Model\Entity\DrawCode;

Route::post('code', function () {
    $id = @App::$request["id"];
    unset(App::$request['amount']);
    if ($id) {
      
    } else {
        DrawCode::isExists('code=? and draw_id=?', [App::$request['code'], App::$request['draw_id']]);
        DrawCode::insert(App::$request);
    }
});

Route::get('', function () {
    App::$response["result"] = Draw::db()->select("p.*,concat('" . BASE_URL . "',p.draw_file) as file")->from("draw p")
        ->where("p.id=?", App::$request["id"])->execute()->fetchObject();
});

Route::get('resume', function () {

    $req = ShQuery::db()->select("count(p.id) as total,
            sum(CASE WHEN amount is not null THEN 1 ELSE 0 END) as  winner, 
            sum(IFNULL(amount,0)) as amount")
        ->from("draw_code p")->where("p.draw_id=?", App::$request["id"]);

    $req = $req->execute()->fetchObject();

    App::$response["result"] = $req;
});

Route::get("winner", function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    $draw = Draw::find('id=?', $id);
    App::$response['result'] =  DrawCode::db()->select('p.*')->from("draw_code p")->where('amount is not null and draw_id=?', $id)->orderBy('sort asc')
        ->limit($draw->current_winner, 0)
        ->execute()->fetchAll();
});

Route::get("all", function () {

    App::$response =  Func::pagi("p.*,
    (select sum(CASE WHEN amount is not null THEN 1 ELSE 0 END) from draw_code where p.id=draw_id limit 1) winner,
    (select sum(IFNULL(amount,0)) from draw_code where p.id=draw_id limit 1) amount", function ($statement) {
        $req = Draw::db()->select($statement)->from("draw p")->where('is_show is not null')->orderBy('id desc');

        if (@App::$request['is_end']) {
            $req = $req->where("event_date>?", strtotime('now'));
        } else {
            $req = $req->where("event_date<?", strtotime('now'));
        }
        return $req;
    });
});
