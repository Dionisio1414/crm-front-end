<?php

namespace App\Tariffs\Repositories;

use App\Tariffs\Model\Step as Model;
use App\Core\Repositories\CoreRepository;
use App\Languages\Repositories\LanguageRepositories;
use Illuminate\Http\Request;

/**
 * Class CharacteristicRepository.
 */
class StepRepository extends CoreRepository
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

    public function getSteps()
    {
        $steps = $this->model()->get();

        foreach ($steps as $step) {
            $step->items = $this->languageRepository->_setSingleLanguage($step->items, $this->lang);
        }
        return $steps;
    }
}
