<?php


namespace App;


class City
{
    public $countryName;
    public $countryDescr;
    public $regionName;
    public $regionDescr;
    public $name;
    public $descr;

    public static function make($data)
    {
        $model = new self();
        $model->countryName = $data['country_name'];
        $model->countryDescr = $data['country_descr'];
        $model->regionName = $data['region_name'];
        $model->regionDescr = $data['region_descr'];
        $model->name = $data['city_name'];
        $model->descr = $data['city_descr'];

        return $model;
    }
}