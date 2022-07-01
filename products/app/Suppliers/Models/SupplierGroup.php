<?php

namespace App\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Model\Yesno;

//use App\Core\Helpers\DirectoryDefaultValue;

class SupplierGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'parent_id', 'order', 'archive'];

    protected $hidden = ['archive'];

    protected $cast = [
        'archive' => 'boolean'
    ];

    public function children()
    {
        return $this->hasMany(SupplierGroup::class, 'parent_id')
            ->with('children')
            ->where('archive', Yesno::NO);
    }

    //recursive SupplierGroup

    private $descendants = [];

    public function recursiveChildren()
    {
        return $this->children()->with('recursiveChildren');
    }

    public function hasChildren()
    {
        if ($this->recursiveChildren->count()) {
            return true;
        }

        return false;
    }

    public function findDescendants(SupplierGroup $group)
    {
        $this->descendants[] = $group->id;

        if ($group->hasChildren()) {
            foreach ($group->recursiveChildren as $child) {
                $this->findDescendants($child);
            }
        }
    }

    public function getDescendants(SupplierGroup $group)
    {
        $this->findDescendants($group);
        return $this->descendants;
    }
}
