<?php

require_once "./classes/sasa/autoload.php";

$db = new \database\DB(DB_DSN, DB_USER, DB_PASS);

class ShQuery
{
    public static $table_name;
    public static function db()
    {
        global $db;
        return $db;
    }

    public static function fields($request, $type = "add")
    {
        $table = App::db()->xQuery("DESCRIBE " . static::$table_name)->fetchAll();
        $data = [];
        $data_null = [];
        $numerics = ['BIT', 'TINYINT', 'BOOL', 'BOOLEAN', 'SMALLINT', 'MEDIUMINT', 'INT', 'INTEGER', 'BIGINT', 'FLOAT', 'DOUBLE', 'DECIMAL', 'DEC'];
        foreach ($table as $key => $col) {
            $find = false;
            foreach (array_keys($request) as  $key) {
                if (is_string($request[$key])) {
                    $request[$key] = trim($request[$key]);
                }

                $field_type = explode("(", $col['Type'], 2)[0];

                if ($col["Field"] ==  $key) {

                    //Truncate
                    if ($field_type == 'varchar') {
                        $size = preg_replace('/[^0-9]/', '', $col["Type"]);
                        $request[$key] = substr($request[$key], 0, $size);
                    }

                    if (in_array(strtoupper($field_type), $numerics) && is_string($request[$key])) {
                        $request[$key] =    (float) $request[$key];
                    }
                    $data[$col["Field"]] = $request[$key];
                    if ($field_type ==  "tinyint") {
                        $data[$col["Field"]] = $request[$key] ? 1 : 0;
                    }
                    if (empty($data[$col["Field"]]))  $data[$col["Field"]] = null;
                    $find = true;
                }
            }
            if ($type == "add" && !isset($request["id"])) {
                if ((!$find || $data[$col["Field"]] == "") && $col["Null"] == "NO" && $col["Default"] == "" && $type == "add" && $col["Field"] != "id") {
                    $data_null[] = $col["Field"];
                }
            } else {
                if ($type != "add") {
                    if (isset($data["id"]))
                        unset($data["id"]);
                }
            }
        }
        if (count($data_null) != 0) {
            Func::error("IS_NULL", 400, implode(",", $data_null));
        }
        return $data;
    }


    public static function medium($query, $count = null)
    {
        if ($count)
            $count = $query->execute()->rowCount();

        $predict = $query->limit(ceil($count / 2), ceil($count / 4))->execute()->fetchAll(\PDO::FETCH_COLUMN);
        if (count($predict) != 0)
            $average = ceil(array_sum($predict) / count($predict));
        else $average = 0;

        return ceil((int) $average / 100) * 100;
    }

    public static function isExists($where, $where_params = null)
    {
        if (
            self::db()->select("*")->from(static::$table_name)
            ->where($where, $where_params)->execute()->rowCount()
            && \Func::error("ALREADY_EXISTS", 400)
        );
    }

    public static function notExists($where, $where_params = null)
    {
        if (
            self::db()->select("*")->from(static::$table_name)
            ->where($where, $where_params)->execute()->rowCount()
            && \Func::error("NOT_EXISTS", 400)
        );
    }

    public static function lastInsert($statement = "*")
    {
        return self::db()->select($statement)
            ->from(static::$table_name)->limit(1)
            ->orderBy("id desc")
            ->execute()->fetchObject();
    }

    public static function lastId()
    {
        return self::db()->lastInsertId();
    }

    public static function save($data, $key = "id")
    {
        self::db()->save(static::$table_name, self::fields($data),  $key);
    }


    public static function insert($data)
    {
        self::db()->insert(static::$table_name, self::fields($data));
    }

    public static function update($data, $where, $where_params = null)
    {
        return self::db()->update(static::$table_name, self::fields($data, "update"), $where, $where_params)->rowCount();
    }

    public static function delete($where, $where_params = null)
    {
        return self::db()->delete(static::$table_name, $where, $where_params)->rowCount();
    }

    public static function find($where, $where_params = null, $statement = "*")
    {
        return  self::db()->select($statement)->from(static::$table_name)
            ->Where($where, $where_params)->limit(1)->execute()->fetchObject();
    }

    public static function findAll($where = null, $where_params = null, $statement = "*", $max = null)
    {
        $table = App::db()->xQuery("DESCRIBE " . static::$table_name)->fetchAll()[0];

        $req =  self::db()->select($statement)->from(static::$table_name)->orderBy($table["Field"] . " desc");
        if ($where != null) $req = $req->Where($where, $where_params);
        if ($max != null) {
            $req->limit($max);
        }
        return    $req->execute()->fetchAll();
    }
}
