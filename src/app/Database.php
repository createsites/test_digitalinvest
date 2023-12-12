<?php


namespace App;


use Illuminate\Database\Capsule\Manager as Capsule;

class Database {
    function __construct() {
        $capsule = new Capsule;
        $capsule->addConnection([
            "driver" => env('DB_DRIVER', 'mysql'),
            "host" => env('DB_HOST', 'localhost'),
            "database" => env('DB_NAME', 'geography'),
            "username" => env('DB_USER', 'root'),
            "password" => env('DB_PASS', ''),
            "charset" => "cp1251",
            "collation" => "cp1251_general_ci",
            "prefix" => "",
        ]);

        $capsule->bootEloquent();
    }
}
