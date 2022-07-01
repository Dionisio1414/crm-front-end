<?php

namespace App\Suppliers\Models\Adresses;

use Illuminate\Database\Eloquent\Model;
//use App\Core\Helpers\DirectoryDefaultValue;

class SupplierIndividualsAdress extends Model
{
    protected $table = 'supplier_individuals_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'street',
        'number_housing',
        'country_id',
        'region_id',
        'city_id'
    ];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
