<?php

namespace App\Tariffs\Repositories;

use App\Languages\Repositories\LanguageRepositories;
use App\Tariffs\Model\Tariff as Model;
use App\Core\Repositories\CoreRepository;
use Illuminate\Http\Request;
use App\Tariffs\Model\CustomColumn;

/**
 * Class CharacteristicRepository.
 */
class TariffRepository extends CoreRepository
{
    protected $languageRepository, $lang;

    //TODO change to core repository
    public function __construct(Request $request, LanguageRepositories $languageRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->lang = $request->lang ?? $this->languageRepository->getDefaultLanguage()->code;
        $this->model = app($this->getModelClass());
    }

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getTariffs($data=[])
    {
        $tariffs = $this->model()->get();


        $index = 0;
        //TODO change tarif (recomendate)
        $rand = rand(0,2);
        foreach ($tariffs as $tariff) {
            $tariff->title = $this->languageRepository->_setSingleLanguageRelation($tariff->detail, $this->lang);
            unset($tariff->detail);

            $tempArray = [];
            foreach ($tariff->custom_column as $customColumn)
            {
                $title = CustomColumn::find($customColumn['id'])->title;
                $customColumn['title'] = $this->languageRepository->_setSingleLanguageArray($title, $this->lang);
                if(!isset($customColumn['active'])){
                    $customColumn['active'] = 0;
                }
                $tempArray[] = $customColumn;
            }
            unset($tariff->custom_column);
            $tariff->custom_column = $tempArray;

            if($data){
                $tariff->isRecommend = false;
                //TODO change tarif (recomendate)
                if($index == $rand){
                    $tariff->isRecommend = true;
                }
            }

            $index++;
        }

        return $tariffs;
    }
}
