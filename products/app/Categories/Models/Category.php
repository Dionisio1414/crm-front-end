<?php

namespace App\Categories\Models;

use App\Core\Model\Yesno;
use App\Directories\Models\Units\DirectoryUnit;
use App\FileManager\Models\FileManager;
use App\Nomenclatures\Models\Nomenclature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Characteristics\Models\Characteristic;
use App\Properties\Models\Property;

class Category extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'title', 'desc', 'image_id', 'order', 'status', 'archive', 'identifier_fullness', 'identifier_successful', 'unit_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    //protected $with = ['children'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('archive', function (Builder $builder) {
            $builder->where('archive', Yesno::NO);
        });
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children')->where('archive', 0)->orderBy('order', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->with('parent')->where('archive', 0)->orderBy('order', 'ASC');
    }

    public function unit()
    {
        return $this->belongsTo(DirectoryUnit::class, 'unit_id');
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'category_characteristic', 'category_id', 'characteristic_id');
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'category_property', 'category_id', 'property_id');
    }

    public function image()
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }

    public function nomenclatures()
    {
        return $this->hasMany(Nomenclature::class, 'nomenclature_id', 'id');
    }


    //recursive Categories

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

    public function findDescendants(Category $category)
    {
        $this->descendants[] = $category->id;

        if ($category->hasChildren()) {
            foreach ($category->recursiveChildren as $child) {
                $this->findDescendants($child);
            }
        }
    }

    public function getDescendants(Category $category)
    {
        $this->findDescendants($category);
        return $this->descendants;
    }
}
