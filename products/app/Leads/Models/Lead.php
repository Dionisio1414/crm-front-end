<?php

namespace App\Leads\Models;

use App\Categories\Models\Category;
use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Directories\Models\Employees\DirectoryEmployee;
use App\Directories\Models\Organizations\DirectoryOrganization;
use App\Leads\Models\LeadsDelivery;
use App\Nomenclatures\Models\Nomenclature;
use Illuminate\Database\Eloquent\Model;
//use App\Core\Helpers\DirectoryDefaultValue;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['archive'];

    protected $with = ['cells'];

    protected $appends = ['manager'];

    protected $casts = [
        'archive'=>'boolean',
    ];

    public function cells()
    {
        return $this->hasMany(LeadsDetail::class, 'lead_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(DirectoryEmployee::class, 'id', 'manager_id');
    }

    public function delivery()
    {
        return $this->hasOne(LeadsDelivery::class, 'lead_id', 'id');
    }

    public function leadStatus()
    {
        return $this->hasOne(LeadsStatus::class, 'id', 'lead_status_id');
    }

    public function organization()
    {
        return $this->hasOne(DirectoryOrganization::class, 'id', 'organization_id');
    }

    public function currency()
    {
        return $this->hasOne(DirectoryCurrency::class, 'id', 'currency_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_lead', 'lead_id', 'category_id');
    }

    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_lead', 'lead_id', 'nomenclature_id')->withPivot('balance', 'is_packing','price', 'percent');
    }

    public function getManagerAttribute()
    {
        return $this->employee->full_name ?? null;
    }

    public function getFullNameAttribute()
    {
        return $this->title ?? $this->title_last_name . ' ' . $this->title_first_name . ' ' .  $this->title_middle_name;
    }
}
