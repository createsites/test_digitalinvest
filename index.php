<?php

namespace App;


// autoload php classes
// todo: it's better to use composer PSR-4 autoload
define('SRC_PATH', __DIR__ . '/src');
spl_autoload_register(function ($class) {
    $path = SRC_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

header('Content-Type: text/html; charset=windows-1251');

// db connection
try {
    $db = DB::getInstance();
    $db->init();

} catch (\Exception $e) {
    die($e->getMessage());
}

$controller = new Controller();

$arResult = $controller->getModels();

// rendering output
$controller->showModels($arResult);
