<?php

namespace App\Core\Helpers;

class UsefulHelper
{
    public static function iterateOverArray($array)
    {
        foreach ($array as $nomenclature) {
            $nomenclature_array[] = $nomenclature;
        }
        return $nomenclature_array;
    }
}
