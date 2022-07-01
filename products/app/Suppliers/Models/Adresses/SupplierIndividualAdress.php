<?php

namespace App\Suppliers\Models\Adresses;

use Illuminate\Database\Eloquent\Model;
//use App\Core\Helpers\DirectoryDefaultValue;

class SupplierIndividualAdress extends Model
{
    protected $table = 'supplier_individual_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'supplier_id',
        'individual_address_id',
    ];

    protected $with = ['individual_address'];

    public function individual_address()
    {
        return $this->hasOne(SupplierIndividualsAdress::class, 'id', 'individual_address_id');
    }

    public $timestamps = false;
}
