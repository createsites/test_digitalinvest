<?php

namespace App;


use App\Controllers\Controller;
use Dotenv\Dotenv;

header('Content-Type: text/html; charset=windows-1251');

require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

new Database();

(new Controller())->actionIndex();
