<?php

namespace App\Countries;

use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;
use MenaraSolutions\Geographer\State;
use MenaraSolutions\Geographer\City;

class Countries
{
    protected $earth;

    public function __construct()
    {
        $this->earth = new Earth();
    }

    public function getCountries()
    {
        return $this->earth->getCountries()->toArray();
    }

    public function getRegions($code)
    {
        return $this->getCountry($code)->getStates()->toArray();
    }

    public function getCities($code)
    {
        return $this->getRegion($code)->getCities()->toArray();
    }

    public function getCountry($code, $lang=null)
    {
        $country = Country::build($code);
        if($lang){
            $country->setLocale($lang);
        }

        return $country;
    }

    public function getCity($code, $lang=null)
    {
        $city = City::build($code);
        if($lang){
            $city->setLocale($lang);
        }

        return $city;
    }

    public function getCityName($code, $lang=null)
    {
        $city = $this->getCity($code, $lang);
        return $city->getName();
    }

    public function getCountryShortName($code, $lang=null)
    {
        $country = $this->getCountry($code, $lang);
        return $country->getShortName();
    }

    public function getCountryLongName($code, $lang=null)
    {
        $country = $this->getCountry($code, $lang);
        return $country->getLongName();
    }

    /* We are waiting for development from the creator of the plugin capital and timezone*/
//    public function getCapital($code, $lang=null)
//    {
//        $country = $this->getCountry('UA', $lang);
//        return $country->getCapital();
//    }

    public function getRegion($code, $lang=null)
    {
        $region = State::build($code);
        if($lang){
            $region->setLocale($lang);
        }

        return $region;
    }

    public function getRegionLongName($code, $lang=null)
    {
        $region = $this->getRegion($code, $lang);
        return $region->getLongName();
    }

    /* Temp Capital */
    public function getCapitalTemp($code, $lang=null)
    {
        $capital = null;
        switch ($code) {
            case 'UA':
                $capital = '703448';
                break;
            case 'RU':
                $capital = '524901';
                break;
            case 'BY':
                $capital = '625144';
                break;
            case 'PL':
                $capital = '756135';
                break;
            case 'IT':
                $capital = '3169070';
                break;
            case 'CN':
                $capital = '1816670';
                break;
            case 'TR':
                $capital = '323786';
                break;
            case 'US':
                $capital = '4140963';
                break;
            case 'DE':
                $capital = '2950159';
                break;
        }

        if($capital){
            $city = City::build($capital);
            $city = $city->setLocale($lang)->toArray();

            return $city['name'];
        }

        return '';
    }

    public function getTimeZoneTemp($code, $lang=null)
    {
        $capital = null;
        switch ($code) {
            case 'UA':
                $capital = 'UTC+2';
                break;
            case 'RU':
                $capital = 'UTC+3';
                break;
            case 'BY':
                $capital = 'UTC+3';
                break;
            case 'PL':
                $capital = 'UTC+1';
                break;
            case 'IT':
                $capital = 'UTC+1';
                break;
            case 'CN':
                $capital = 'UTC+8';
                break;
            case 'TR':
                $capital = 'UTC+2';
                break;
            case 'US':
                $capital = 'UTC-5';
                break;
            case 'DE':
                $capital = 'UTC+1';
                break;

        }

        if($capital){
            return $capital;
        }

        return '';
    }
    /* End Temp Capital */

    public function getCountryCodes()
    {
        $countries = $this->getCountries();

        $data = [];
        foreach ($countries as $country){
            $data[] = $country['code'];
        }
        return $data;
    }

    public function getPhoneCodes()
    {
        $countries = $this->getCountries();

        $data = [];
        foreach ($countries as $country){
            $data[$country['phonePrefix']] = $country['code'];
        }
        return $data;
    }
}
