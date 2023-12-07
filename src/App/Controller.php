<?php


namespace App;


class Controller
{
    public function userLang()
    {
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
        return $lang;
    }

    public function getModels()
    {
        // determine the language
        $lang = $this->userLang();

        // this solution is using for the test task
        // in a real project using ORM
        // retrieve results from DB
        $arResult = (new self())->dbQuery($lang);

        // associating with models
        $arModels = [];
        foreach ($arResult as $item) {
            $arModels[] = (new City())->make($item);
        }

        return $arModels;
    }

    private function dbQuery($lang)
    {
        $db = DB::getInstance();

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
        $statement = $db->prepare('
            SELECT ' . $sqlSelect . '
            FROM country
            LEFT JOIN city ON country.id = city.c_country_id
            LEFT JOIN region ON city.c_region_id = region.id
            WHERE country.glob_region_id = :glob_reg_id
            ORDER BY ' . $sqlOrder . '
            LIMIT ' . Config::SQL_REQUEST_LIMIT
        );
        $statement->execute([
                                'glob_reg_id' => Config::GLOB_REGION_EUROPE
                            ]);

        // getting results
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function showModels($models)
    {
        if (!empty($models)) {
            $lastCountry = false;
            $lastRegion = false;

            foreach ($models as $city) {
                // displaying the country
                if (!$lastCountry || $lastCountry != $city->countryName) {
                    $this->render('country', $city->countryDescr, $city->countryName);
                    $lastCountry = $city->countryName;
                }
                // displaying the region
                $regionName = $city->regionName != null ? $city->regionName : false;
                if ($regionName && (!$lastRegion || $lastRegion != $regionName)) {
                    $this->render('region', $city->regionDescr, $city->regionName);
                    $lastRegion = $regionName;
                }
                // displaying the city
                if (!$regionName) {
                    $this->render('city_without_region', $city->descr, $city->name);
                }
                else {
                    $this->render('city', $city->descr, $city->name);
                }
            }
        }
    }

    private function render($template, $descr, $name)
    {
        $displayStringFormat = file_get_contents(__DIR__ . '/views/' . $template . '.php');
        echo sprintf($displayStringFormat, $descr, $name);
    }
}