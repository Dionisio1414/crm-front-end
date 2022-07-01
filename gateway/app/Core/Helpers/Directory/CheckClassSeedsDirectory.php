<?php

namespace App\Core\Helpers\Directory;

class CheckClassSeedsDirectory
{
    public static $directoryClasses = [
        'units' => 'DirectoryUnits',
        'cities' => 'DirectoryCities',
        'countries' =>  'DirectoryCountries',
        'departments' =>  'DirectoryDepartmnets',
        'counterparties_cancellations'=> 'DirectoryCounterpartiesCancellations',
        'counterparties_returns'=> 'DirectoryCounterpartiesReturns',
        'positions'=> 'DirectoryPositions',
        'employee_documents'=> 'DirectoryEmployees',
        'prices_types'=> 'DirectoryTypesPrices',
        'currencies'=> 'DirectoryCurrencies',
        'counterparties'=> 'DirectoryCounterparties',
        'counterparties_contracts' => 'DirectoryCounterpartiesContracts',
        'individuals_documents' => 'DirectoryIndividualsDocumentsHeaders',
        'employees' => 'DirectoryEmployees',
        'users' => 'DirectoryUsers',
    ];

    public static function checkClassSeed($directory)
    {
        return self::$directoryClasses[$directory];
    }
}
