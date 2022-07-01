<?php

namespace App\Directories\Models\Organizations;

use Illuminate\Database\Eloquent\Model;

class DirectoryOrganization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'is_default',
        'archive',
        'order'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive'];

    protected $casts = [
        'is_default'=>'boolean',
        'archive'=>'boolean'
    ];

    protected $with = ['main', 'contact'];

    public function main()
    {
        return $this->hasOne(DirectoryOrganizationsDetailsMain::class, 'organization_id', 'id');
    }

    public function contact()
    {
        return $this->hasOne(DirectoryOrganizationsDetailsContact::class, 'organization_id');
    }
}
