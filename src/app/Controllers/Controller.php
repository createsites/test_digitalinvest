<?php


namespace App\Controllers;


use App\Models\Country;

class Controller
{
    private string $lang;

    public function __construct()
    {
        $this->lang = $this->userLang();
    }

    public function userLang(): string
    {
        return match (htmlspecialchars($_GET['user_lang'])) {
            'eng' => 'eng',
            'ger' => 'ger',
            default => 'rus',
        };
    }

    public function actionIndex()
    {
        // determine DB fields names depends of the language
        $nameByLang = 'c_name_' . $this->lang;
        $descrByLang = 'c_descr_' . $this->lang;
        $regionNameByLang = 'r_name_' . $this->lang;
        $regionDescrByLang = 'r_descr_' . $this->lang;

        // getting results
        $queryBuilder = Country::with(['cities' => ['region']])
            ->where('glob_region_id', env('GLOB_REGION_EUROPE', 1))
            ->orderBy($nameByLang)
            ->limit(env('SQL_REQUEST_LIMIT', 100));

        // displaying results
        foreach ($queryBuilder->get() as $country) {

            // country
            $this->render('country', $country->$descrByLang, $country->$nameByLang);

            // loop of the cities
            $cities = $country->cities->sortBy($nameByLang);
            $lastRegion = false;
            foreach($cities as $city) {

                // region
                if (
                    $city->region
                    && (!$lastRegion || $lastRegion != $city->region->$regionNameByLang)
                ) {
                    $this->render('region', $city->region->$regionDescrByLang, $city->region->$regionNameByLang);
                    $lastRegion = $city->region->$regionNameByLang;
                }

                // city
                if (!$city->region) {
                    $this->render('city_without_region', $city->$descrByLang, $city->$nameByLang);
                }
                else {
                    $this->render('city', $city->$descrByLang, $city->$nameByLang);
                }
            }
        }
    }

    private function render($template, $descr, $name)
    {
        $displayStringFormat = file_get_contents(__DIR__ . '/../views/' . $template . '.php');
        echo sprintf($displayStringFormat, $descr, $name);
    }
}