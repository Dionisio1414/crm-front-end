<?php

namespace App\Documents\Repositories\Purchases;

use App\Documents\Models\Purchases\DocumentPurchasesHeader as Model;
use App\Documents\Models\Purchases\DocumentPurchasesActivityHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\TitleJson;

/**
 * Class ReceiptStocksHeaderRepository.
 */
class PurchaseHeaderRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getHeaders()
    {
        return TitleJson::get($this->model()->get(), $this->lang);
    }

    public function getActivityPurchasesHeaders()
    {
        return TitleJson::get(DocumentPurchasesActivityHeader::get(), $this->lang);
    }

    public function getHeader($id)
    {
        return $this->model()->find($id);
    }

    public function updateHeaders()
    {
        foreach ($this->request->data as $item)
        {
            $this->getHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }
}
