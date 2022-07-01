<?php

namespace App\Documents\Repositories\ReceiptStocks;

use App\Core\Helpers\TitleJson;
use App\Core\Repositories\Traits\RepositoryDocumentTraits;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Documents\Models\ReceiptStocks\DocumentReceiptStock as Model;
use App\Documents\Models\ReceiptStocks\DocumentReceiptStocksHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class ReceiptStockRepository.
 */
class ReceiptStockRepository extends CoreRepository
{
    use RepositoryTraits;

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function modelVisible()
    {
        return $this->model()->where('archive', Yesno::NO);
    }

    public function getAll()
    {
        return $this->modelVisible()->paginate($this->request->paginate);
    }

//    public function getDocument($id)
//    {
//
//    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
    }

    public function getItemsTable()
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s);
        }else{
            $data = $this->getAll();
        }
        $data = $this->getTable($data, DocumentReceiptStocksHeader::class);

        return $data->toArray();
    }

    public function createItem($document_id)
    {
        return $this->modelVisible()->create(array_merge($this->request->all(), ['document_id' => $document_id]));
    }

    public function createOrUpdateCells($id, $nomenclatures = null)
    {

        $item = $this->modelVisible()->find($id);
        if (isset($item->organization->title)) {
            $convert_id = mb_strtoupper(substr($item->organization->title, 0,2)) . '-' . str_pad($item->document_id, 8, '0', STR_PAD_LEFT);
        } else {
            $convert_id = str_pad($item->document_id, 8, '0', STR_PAD_LEFT);
        }
        $nomenclature_price_amount = 0;

        if(isset($nomenclatures)){
            foreach ($nomenclatures as $value){
                $nomenclature_price_amount = $nomenclature_price_amount + ($value['price'] * $value['balance_base']);
            }
        }

        $data = [
            'date' => $item->document->date,
            'document_type' => $this->getDirectoryCore('documents', 2),
            'id' => $convert_id,
            'convert_id' => $convert_id,
            'amount' => $nomenclature_price_amount . ' ' . TitleJson::getArray($item->currency->title, $this->lang) ?? null,
            'receipt_date_scheduled' => $item->receipt_date_scheduled ?? null,
            'receipt_date_actual' => $item->receipt_date_actual ?? null,
            'warehouse' => optional($item->warehouse)->title ?? null,
            'responsible' => optional($item->responsible)->full_name ?? null,
            'organization' => optional($item->organization)->title ?? null,
            //'supplier_document_date' => $item->supplier_document_date ?? null,
            //'supplier_document_number' => $item->supplier_document_number ?? null,
            'directory_return' => optional($item->directory_return)->id ? TitleJson::getArray($item->directory_return->title, $this->lang) : null,
            'directory_cancell' =>optional($item->directory_cancell)->id ? TitleJson::getArray($item->directory_cancell->title, $this->lang) : null,
            //'description' => $item->description ?? null,
            'basis' => $item->basis  ?? null,
            'shipping_date_actual' => $item->shipping_date_actual  ?? null,
            'ttn_number' => $item->ttn_number  ?? null,
            'ttn_status' => $item->ttn_number  ?? null,
            'user' => $item->user ?? null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DocumentReceiptStocksHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
        $item->update([
            'organization_convert_id' => $convert_id
        ]);
    }

    public function updateItem($id)
    {
        $document = $this->modelVisible()->where('document_id', $id)->first();
        $document->update($this->request->all());
        return $document->id;
    }

    public function getDocumentId($id)
    {
        return $this->modelVisible()->where('document_id', $id)->first();
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DocumentReceiptStocksHeader::class);
    }

    public function getDocument($id)
    {
        $item = $this->getDocumentId($id);
        return $this->getOneTable($item, DocumentReceiptStocksHeader::class);
    }

    public function updateCommentDocument($id, $comments)
    {
        return $this->model()->where('document_id', $id)->update([
            'comments'  => $comments
        ]);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }
}
