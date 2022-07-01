<?php

namespace App\Core\Helpers\Earth;

use App\Core\Model\Yesno;
use App\Countries\Countries;
use Geocoder\Laravel\Facades\Geocoder;

class ImportCountries
{
    static $defaultCountryCode = 'UA';
    static $defaultCountryMainId = 232; // -1
    static $defaultCountries = ['', 83, 236, 228, 46, 110, 177, 21, 183, 233];
    static $defaultCityMainId = 64;
    static $defaultRegionMainId = 20; // -1

    static $rezult = [
        'main' => [],
        'detail' => []
    ];

    static $counter = 1;

    /* Get Countries */
    public static function _getDataMainDetail($languages)
    {
        $countries = new Countries();
        foreach ($countries->getCountries() as $key=>$country){
            /* Create main */
            $default_id = array_search($key+1, self::$defaultCountries);

            self::_createMainTable($key+1, $key == self::$defaultCountryMainId ? Yesno::YES : Yesno::NO, $default_id ?? null);

            /* Create detail */
            self::_createDetailTable($key+1, $country, $languages);
        }

        $responce = self::$rezult;

        /* Clear Responce */
        self::$rezult = [
            'main' => [],
            'detail' => []
        ];

        return $responce;
    }

    /* Get Regions */
    public static function _getRegionsDataMainDetail($languages)
    {
        $countries = new Countries();
        foreach ($countries->getRegions(self::$defaultCountryCode) as $key=>$region){
            /* Create main */
            self::_createRegionsMainTable($key+1, $key == self::$defaultRegionMainId ? Yesno::YES : Yesno::NO, self::$defaultCountryMainId);

            /* Create detail */
            self::_createRegionsDetailTable($key+1, self::$defaultCountryCode, $region,  $languages);
        }

        $responce = self::$rezult;

        /* Clear Responce */
        self::$rezult = [
            'main' => [],
            'detail' => []
        ];

        return $responce;
    }

    /* Get Cities */
    public static function _getCitiesDataMainDetail($languages)
    {
        $countries = new Countries();
        $count = 1;
        foreach ($countries->getRegions(self::$defaultCountryCode) as $keyRegion=>$region){
            foreach ($countries->getCities($region['code']) as $keyCity=>$city){
                /* Create main */
                self::_createCitiesMainTable($count,$count == self::$defaultCityMainId ? Yesno::YES : Yesno::NO, $keyRegion+1);

                /* Create detail */
                self::_createCitiesDetailTable($count, self::$defaultCountryCode, $city,  $languages, $region);

                $count ++;
            }
        }
        //dd(self::$rezult);
        $responce = self::$rezult;

        /* Clear Responce */
        self::$rezult = [
            'main' => [],
            'detail' => []
        ];

        return $responce;
    }

    protected static function _createRegionsMainTable($id, $is_default = Yesno::NO, $country_id = null)
    {
        self::$rezult['main'][] = [
            'id' => $id,
            'is_default' => $is_default,
            'country_id' => $country_id+1,
            'is_editable' => Yesno::YES,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }

    protected static function _createCitiesMainTable($id, $is_default = Yesno::NO, $region_id = null)
    {
        self::$rezult['main'][] = [
            'id' => $id,
            'is_default' => $is_default,
            'region_id' => $region_id,
            'is_editable' => Yesno::YES,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }

    protected static function _createMainTable($id, $is_default = Yesno::NO, $default = null)
    {
        self::$rezult['main'][] = [
            'id' => $id,
            'is_default' => $is_default,
            'is_editable' => Yesno::NO,
            'default'    => $default,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ];
    }

    protected static function _createCitiesDetailTable($id, $default_code, $data, $languages, $region)
    {
        $convert_id = str_pad($id, 10, '0', STR_PAD_LEFT);

        /* Get zip code */
        $geocodeCity = Geocoder::geocode($data['latitude'] . ',' . $data['longitude'])->get();
        $city = optional($geocodeCity)->toArray();
        $zip_code = null;
        if(count($city)){
            $cityArray = $city[0]->toArray();
            $zip_code = $cityArray['postalCode'];
        }
        /* end */

        $newData = [
            '1' => self::convertToJsonLanguages($convert_id, $languages),
            '2' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getCityName'),
            '3' => self::convertToJsonLanguagesTranslates($region['code'], $languages, 'getRegionLongName'),
            '4' => self::convertToJsonLanguagesTranslates($default_code, $languages, 'getCountryShortName'),
            '5' => self::convertToJsonLanguages($zip_code, $languages),
        ];

        foreach ($newData as $key => $item) {
            self::$rezult['detail'][] = [
                'id' => self::$counter++,
                'title' => json_encode($item),
                'city_id' => $id,
                'header_id' => $key
            ];
        }
    }

    protected static function _createRegionsDetailTable($id, $default_code, $data, $languages)
    {
        $convert_id = str_pad($id, 10, '0', STR_PAD_LEFT);
        $newData = [
            '1' => self::convertToJsonLanguages($convert_id, $languages),
            '2' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getRegionLongName'),
            '3' => self::convertToJsonLanguagesTranslates($default_code, $languages, 'getCountryShortName')
        ];

        foreach ($newData as $key => $item) {
            self::$rezult['detail'][] = [
                'id' => self::$counter++,
                'title' => json_encode($item),
                'region_id' => $id,
                'header_id' => $key
            ];
        }
    }

    protected static function _createDetailTable($id, $data, $languages)
    {
            $newData = [
                '1' => self::convertToJsonLanguages($data['numericCode'], $languages),
                '2' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getCountryShortName'),
                '3' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getCountryLongName'),
                '4' => self::convertToJsonLanguages($data['code'], $languages),
                '5' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getCapitalTemp'),
                '6' => self::convertToJsonLanguages($data['phonePrefix'] ? '+' . $data['phonePrefix'] : '', $languages),
                '7' => self::convertToJsonLanguagesTranslates($data['code'], $languages, 'getTimeZoneTemp'),
            ];

            foreach ($newData as $key => $item) {
                self::$rezult['detail'][] = [
                    'id' => self::$counter++,
                    'title' => json_encode($item),
                    'country_id' => $id,
                    'header_id' => $key
                ];
            }
    }

    protected static function convertToJsonLanguages($data, $languages)
    {
            foreach ($languages as $language) {

                $newData[] = (object)[
                    'title' => $data,
                    'code'  => $language->code
                ];
            }

        return $newData;
    }

    protected static function convertToJsonLanguagesTranslates($data, $languages, $function)
    {
        $country = new Countries();

        foreach ($languages as $language) {

            $lang = $language->code;
            if($language->code == mb_strtolower(self::$defaultCountryCode)){
                $lang = 'uk';
            }

            $newData[] = (object)[
                'title' => $country->$function($data, $lang),
                'code'  => $language->code
            ];
        }

        return $newData;
    }
}




