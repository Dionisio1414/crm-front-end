<?php

namespace App\Suppliers\Models;

use App\Directories\Models\Employees\DirectoryEmployee;
use Illuminate\Database\Eloquent\Model;
use App\Suppliers\Models\Adresses\SupplierIndividualAdress;
use App\Suppliers\Models\Adresses\SupplierDeliveryAdress;

class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title_supplier',
        'title_company',
        'title_supplier_middle_name',
        'title_supplier_last_name',
        'title_supplier_first_name',
        'partner_type_id',
        'group_id',
        'is_legal_equal_actual_address',
        'is_delivery_equal_actual_address',
        'date_of_birth',
        'sex_id',
        'is_foreign_company',
        'currency_id',
        'legal_address_street',
        'legal_address_number_housing',
        'phone',
        'email',
        'partner_inn',
        'partner_edrpou',
        'legal_address_country_id',
        'legal_address_region_id',
        'legal_city_id',
        'archive',
        'manager_id'
    ];

    protected $hidden = ['archive'];

    protected $with = ['cells', 'actual_address', 'delivery_address', 'contacts'];

    protected $appends = [
        'manager'
    ];

    protected $casts = [
        'archive'=>'boolean',
        'is_legal_equal_actual_address'=>'boolean',
        'is_delivery_equal_actual_address'=>'boolean',
        'is_foreign_company'=>'boolean',
    ];

    public function cells()
    {
        return $this->hasMany(SuppliersDetail::class, 'supplier_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(DirectoryEmployee::class, 'id', 'manager_id');
    }

    public function getManagerAttribute()
    {
        return $this->employee->full_name ?? null;
    }

    public function actual_address()
    {
        return $this->hasMany(SupplierIndividualAdress::class, 'supplier_id', 'id');
    }

    public function delivery_address()
    {
        return $this->hasMany(SupplierDeliveryAdress::class, 'supplier_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class, 'supplier_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->title_company ?? $this->title_supplier_middle_name . ' ' . $this->title_supplier_first_name . ' ' . $this->title_supplier_last_name;
    }
}
