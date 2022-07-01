<?php

namespace App\Suppliers\Models;

use App\Directories\Models\Counterparties\DirectoryCounterparty;
use Illuminate\Database\Eloquent\Model;
use App\Core\Model\Yesno;

class SupplierContact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'middle_name',
        'last_name',
        'first_name',
        'date_of_birth',
        'email',
        'phone',
        'source_attractions_id',
        'sex_id',
        'position_id',
        'supplier_id',
        'is_default'
    ];

    protected $hidden = ['supplier_id', 'created_at', 'updated_at'];

    protected $appends = ['full_name'];

    protected $with = ['documents'];

    protected $casts = [
      'is_default' => 'boolean'
    ];

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function documents()
    {
        return $this->hasMany(SupplierContactsDocument::class, 'supplier_contact_id', 'id')->where('archive', Yesno::NO);
    }

    public function counterparty()
    {
        return $this->hasOneThrough(DirectoryCounterparty::class, Supplier::class, 'id', 'supplier_id', 'supplier_id');
    }
}
