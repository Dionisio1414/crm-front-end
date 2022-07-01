<?php
namespace App\Models\Directories;

use App\Helpers\TitleJson;
use App\Helpers\Yesno;
use App\Models\Languages\Language;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $table = 'directories';

    protected $fillable = ['header_id', 'directory_id', 'title', 'thumbnail'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $casts = [
        'title' => 'array'
    ];

    protected $appends = ['default_language_title'];

    public function header()
    {
        return $this->hasOne(DirectoryHeader::class, 'id', 'header_id');
    }

    public function getDefaultLanguageTitleAttribute()
    {
        $language = Language::where('code', 'ru')->where('status', Yesno::YES)->first();
        return TitleJson::getArray($this->title, $language->id) ?? '';
    }
}
