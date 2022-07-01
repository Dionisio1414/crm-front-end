<?php
namespace App\Models\Directories;

use App\Helpers\TitleJson;
use App\Helpers\Yesno;
use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Model;

class DirectoryHeader extends Model
{
    protected $table = 'directories_headers';

    protected $fillable = ['title', 'value'];

    protected $appends = ['default_language_title'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'title' => 'array'
    ];

    protected $with = ['directory'];

    public function directory()
    {
        return $this->hasMany(Directory::class, 'header_id', 'id');
    }

    public function getDefaultLanguageTitleAttribute()
    {
        $language = Language::where('code', 'ru')->where('status', Yesno::YES)->first();
        return TitleJson::getArray($this->title, $language->id) ?? '';
    }
}
