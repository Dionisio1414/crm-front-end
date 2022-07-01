<?php

namespace App\Languages\Repositories;

use App\Languages\Model\Language as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class CharacteristicRepository.
 */
class LanguageRepositories extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getLanguageById($id)
    {
        return $this->model()->find($id);
    }

    public function getLanguageByCode($code)
    {
        return $this->model()->where('code', $code)->first();
    }

    public function getDefaultLanguage()
    {
        return $this->model()->first();
    }

    public function _setSingleLanguage($items, $code='')
    {
        $language = $this->getLanguageByCode($code);
        foreach ($items as $item)
        {
            $item->title = collect($item->title)->map(function ($item) use ($language) {
                if($item['language_id'] == $language->id){
                    return $item['title'];
                }
            })->first();
        }

        return $items;
    }

    public function _setSingleLanguageRelation($items, $code='')
    {
        $language = $this->getLanguageByCode($code);

        foreach ($items as $item)
        {
            if($item->language_id == $language->id){
                return $item->title;
            }
        }
        return $items;
    }

    public function _setSingleLanguageArray($items, $code='')
    {
        $language = $this->getLanguageByCode($code);

        foreach ($items as $item)
        {
            if($item['language_id'] == $language->id){
                return $item['title'];
            }
        }
        return $items;
    }

    public function getLanguages()
    {
        return $this->model()->all();
    }

    public function getActiveLanguages()
    {
        return $this->model()->where('status', Yesno::YES)->get();
    }
}
