<?php

namespace App\Models\Languages\Source;

use App\Helpers\Yesno;
use App\Models\Languages\Language;
use Illuminate\Support\Facades\DB;

class Options
{
    public static function _getOptions()
    {
        $languages = Language::where('status',Yesno::YES)->get(['id', DB::raw('title as text')]);
        $options = [];
        foreach ($languages as $language)
        {
            $options[$language->id] = $language->text;
        }
        return $options;
    }
}
