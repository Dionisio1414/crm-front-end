<?php

namespace App\Directories\Models\Departments;

use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\DirectoryDefaultValue;

class DirectoryDepartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_default', 'is_editable', 'archive'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['cells'];

    protected $appends = ['department_number', 'city', 'street', 'house_number'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'archive'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryDepartmentsDetail::class, 'department_id', 'id');
    }

    public function getDepartmentNumberAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryDepartmentsHeader::class, 'department_number');
    }

    public function getCityAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryDepartmentsHeader::class, 'city');
    }

    public function getStreetAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryDepartmentsHeader::class, 'street');
    }

    public function getHouseNumberAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryDepartmentsHeader::class, 'house_number');
    }
}
