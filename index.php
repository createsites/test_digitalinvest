<?php

header('Content-Type: text/html; charset=windows-1251');

// DB settings
// todo don't keep it in the repo, move to .env file
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'geography';

const GLOB_REGION_EUROPE = 1;
const SQL_REQUEST_LIMIT = 100;

// db connection
try {
    $dbHandle = new PDO(
        'mysql:dbname=' . $dbName . ';host=' . $dbHost,
        $dbUser,
        $dbPass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'cp1251'")
    );
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

// determine the language
switch (htmlspecialchars($_GET['user_lang'])) {
    case 'eng':
        $lang = 'eng';
        break;
    case 'ger':
        $lang = 'ger';
        break;
    default:
        $lang = 'rus';
}

// prepare parts of sql request with the exact language
// sql select
$sqlSelect = '
    city.c_name_' . $lang . ' as city_name,
    city.c_descr_' . $lang . ' as city_descr,
    country.c_name_' . $lang . ' as country_name,
    country.c_descr_' . $lang . ' as country_descr,
    region.r_name_' . $lang . ' as region_name,
    region.r_descr_' . $lang . '  as region_descr
';
// and sql order
$sqlOrder = 'country.c_name_' . $lang . ', region.r_name_' . $lang . ', city.c_name_' . $lang;

// executing sql request
$statement = $dbHandle->prepare('
    SELECT ' . $sqlSelect . '
    FROM country
    LEFT JOIN city ON country.id = city.c_country_id
    LEFT JOIN region ON city.c_region_id = region.id
    WHERE country.glob_region_id = :glob_reg_id
    ORDER BY ' . $sqlOrder . '
    LIMIT ' . SQL_REQUEST_LIMIT
);
$statement->execute([
    'glob_reg_id' => GLOB_REGION_EUROPE
]);

// getting results
$arResult = $statement->fetchAll(PDO::FETCH_ASSOC);

// rendering output
if (!empty($arResult)) {
    $lastCountry = false;
    $lastRegion = false;
    // format of a html block
    $displayStringFormat = '<a title="%s" style="padding-left: %dem">%s</a><br>';

    foreach ($arResult as $item) {
        // displaying the country
        if (!$lastCountry || $lastCountry != $item['country_name']) {
            echo sprintf($displayStringFormat, $item['country_descr'], 1, $item['country_name']);
            $lastCountry = $item['country_name'];
        }
        // displaying the region
        $regionName = $item['region_name'] != null ? $item['region_name'] : false;
        if ($regionName && (!$lastRegion || $lastRegion != $regionName)) {
            echo sprintf($displayStringFormat, $item['region_descr'], 2, $regionName);
            $lastRegion = $regionName;
        }
        // displaying the city
        $depth = $regionName ? 3 : 2;
        echo sprintf($displayStringFormat, $item['city_descr'], $depth, $item['city_name']);
    }
}
