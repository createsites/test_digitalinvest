<?php


namespace App;


class Config
{
    // DB settings
    // todo don't keep it in the repo, move to .env file
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'geography';

    // hardcoded region
    const GLOB_REGION_EUROPE = 1;
    // limit of the results just in case
    const SQL_REQUEST_LIMIT = 100;
}