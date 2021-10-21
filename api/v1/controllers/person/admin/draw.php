<?php

use Model\Entity\Draw;
use Model\Entity\DrawCode;

Route::get('resume', function () {

    $req = ShQuery::db()->select("count(p.id) as total,
            sum(CASE WHEN amount is not null THEN 1 ELSE 0 END) as  winner, 
            sum(IFNULL(amount,0)) as amount")
        ->from("draw_code p")->where("p.draw_id=?", App::$request["id"]);

    $req = $req->execute()->fetchObject();

    App::$response["result"] = $req;
});

Route::get("code/all", function () {
    $draw_id = @App::$request["draw_id"];
    Func::emptyCheck([$draw_id]);

    App::$response =  Func::pagi("p.*", function ($statement) use ($draw_id) {
        $req = ShQuery::db()->select($statement)->from("draw_code p")->orderBy('sort asc,amount IS NOT NULL desc,amount desc')->where('draw_id=?', $draw_id);

        if (@App::$request["is_winner"]) {
            $req =  $req->where(
                "amount is not null"
            );
        }

        if (@App::$request["q"]) {
            $q = App::$request["q"];
            $req =  $req->where(
                "code LIKE concat('%',?,'%')",
                [$q]
            );
        }

        return $req;
    });
});

Route::post('code', function () {
    $id = @App::$request["id"];
    if ($id) {
        DrawCode::isExists('code=? and draw_id=? and id!=?', [App::$request['code'], App::$request['draw_id'], $id]);

        DrawCode::save(App::$request);
    } else {
        DrawCode::isExists('code=? and draw_id=?', [App::$request['code'], App::$request['draw_id']]);
        DrawCode::insert(App::$request);
    }
});

Route::post('sort', function () {
    $ids = @App::$request["ids"];
    Func::emptyCheck([$ids]);

    $sids = implode(',', array_fill(0, count($ids), '?'));

    $categories = ShQuery::db()->select("id,sort")->from('draw_code')->where('id in (' . $sids . ')', array_merge($ids))
        ->orderBy("sort asc")->execute()->fetchAll();

    foreach ($ids as $key => $id) {
        if ($key == 0) {
            $sort = $categories[$key]['sort'];
        } else {
            if ($categories[$key]['sort'] <= $categories[$key - 1]['sort']) {
                $sort = $categories[$key - 1]['sort'] + 1;
            } else {
                $sort = $categories[$key]['sort'];
            }
        }
        DrawCode::update(["sort" => $sort], 'id=?', [$id]);
    }

    App::$response["ids"] = $sort;
});



Route::post('generate-code', function () {
    $draw_id = @App::$request["draw_id"];
    $start_interval = @App::$request["start_interval"];
    $end_interval = @App::$request["end_interval"];
    Func::emptyCheck([$draw_id, $start_interval, $end_interval]);

    DrawCode::delete('draw_id=?', $draw_id);
    foreach (range($start_interval, $end_interval) as $code) {

        DrawCode::insert(
            [
                "draw_id" => $draw_id,
                "code" => $code
            ]
        );
    }

    $fp = fopen('static/draw-' . $draw_id . '.csv', 'w');

    fputcsv($fp, array('code'));

    foreach (range($start_interval, $end_interval) as $code) {
        fputcsv($fp, [
            'code' => $code
        ]);
    }

    fclose($fp);


    $draw = Draw::update([
        "draw_file" => 'static/draw-' . $draw_id . '.csv',
        "start_interval" => $start_interval,
        "end_interval" => $end_interval,
    ], 'id=?', $draw_id);

    App::$response['result'] = [
        "draw_file" => BASE_URL . 'static/draw-' . $draw_id . '.csv'
    ];
});


Route::get('', function () {
    App::$response["result"] = Draw::db()->select("p.*,concat('" . BASE_URL . "',p.draw_file) as file")->from("draw p")
        ->where("p.id=?", App::$request["id"])->execute()->fetchObject();
});

Route::get("all", function () {

    App::$response =  Func::pagi("p.*,
    (select sum(CASE WHEN amount is not null THEN 1 ELSE 0 END) from draw_code where p.id=draw_id limit 1) winner,
    (select sum(IFNULL(amount,0)) from draw_code where p.id=draw_id limit 1) amount", function ($statement) {
        $req = Draw::db()->select($statement)->from("draw p");

        $date_range = Func::dateRange(App::$request);
        $req =  $req->where("(p.creation_date >= ? and p.creation_date <= ?) ", [$date_range[0], $date_range[1]]);


        if (isset(App::$request["q"])) {
            $q = App::$request["q"];
            $req =  $req->where(
                "name LIKE concat('%',?,'%') || description LIKE concat('%',?,'%')",
                [$q, $q]
            );
        }

        return $req;
    });
});

Route::post('', function () {

    $id = @App::$request["id"];
    if ($id) {
        Draw::save(App::$request);
    } else {
        App::$request["creation_date"] = strtotime("now");
        Draw::insert(App::$request);
        $id = Draw::lastId();
    }

    App::$response['result'] = [
        "id" => $id
    ];
});

Route::delete('code', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    DrawCode::delete("id=?", $id);
});

Route::delete('', function () {
    $id = @App::$request["id"];
    Func::emptyCheck([$id]);
    Draw::delete("id=?", $id);
});
