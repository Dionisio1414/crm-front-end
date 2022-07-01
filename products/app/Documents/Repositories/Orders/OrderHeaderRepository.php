<?php

namespace App\Documents\Repositories\Orders;

use App\Documents\Models\Orders\DocumentOrdersHeader as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\TitleJson;
use App\Documents\Models\Orders\DocumentOrdersShipmentHeader;

/**
 * Class OrderHeaderRepository.
 */
class OrderHeaderRepository extends CoreRepository
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

    public function getHeader($id)
    {
        return $this->model()->find($id);
    }

    public function getOrdersShipmentHeaders()
    {
        return TitleJson::get(DocumentOrdersShipmentHeader::get(), $this->lang);
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
