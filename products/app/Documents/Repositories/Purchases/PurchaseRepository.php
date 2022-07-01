<?php

namespace App\Documents\Repositories\Purchases;

use App\Core\Helpers\TitleJson;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Documents\Models\Purchases\DocumentPurchase as Model;
use App\Documents\Models\Purchases\DocumentPurchasesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class ReceiptStockRepository.
 */
class PurchaseRepository extends CoreRepository
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
        return $this->model()->orderBy('created_at', 'DESC');
    }

    public function getAll()
    {
        return $this->modelVisible()->whereHas('document', function($query){
            $query->where('archive',Yesno::NO);
        })->paginate($this->request->paginate);
    }

    public function getItemsByQuery($query)
    {
        return $this->searchTitleByJson(
            $this->modelVisible()->whereHas('document', function($q) use ($query){
                $q->where('archive',Yesno::NO);
                if(isset($query['is_capitalized'])){
                    $q->where('status', $query['is_capitalized'] == 'true' ? Yesno::YES : Yesno::NO);
                }
        }), 'cells', $query['s'] ?? '');
    }

    public function getItemsTable()
    {
        /* Get Queries */
        $query = $this->request->query();

        if ($query) {
            $data = $this->getItemsByQuery($query);
        } else {
            $data = $this->getAll();
        }

        $data->makeHidden('supplier');
        $data->makeHidden('document');
        $data->makeVisible('status');

        $data = $this->getTable($data, DocumentPurchasesHeader::class);
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
            //'status' => $item->status_payment_id,
            'status' => $item->status_product_id,
            'convert_id' => $convert_id,
            'user' => optional($item->supplier)->title_supplier ?? null,
            'amount' => $nomenclature_price_amount . ' ' . TitleJson::getArray($item->currency->title, $this->lang) ?? null,
            'receipt_date_scheduled' => $item->receipt_date_scheduled ?? null,
            'receipt_date_actual' => $item->receipt_date_actual ?? null,
            'payment_date_scheduled' => $item->payment_date_scheduled ?? null,
            'payment_date_actual' => $item->payment_date_actual ?? null,
            'status_payment' => $this->getDirectoryImageCore('status_payment', $item->status_payment_id),
            'delivery' => $this->getDirectoryImageCore('delivery_methods', optional($item->delivery)->delivery_methods_id) ?? $this->getDirectoryCore('delivery_methods', optional($item->delivery)->delivery_methods_id),
            'warehouse' => optional($item->warehouse)->title ?? null,
            'organization' => optional($item->organization)->title,
            'supplier_document_date' => $item->supplier_document_date ?? null,
            'supplier_document_number' => $item->supplier_document_number ?? null,
            'ttn_number' => optional($item->delivery)->ttn_number ?? null,
            'ttn_status' => optional($item->delivery)->ttn_status ?? null,
            'terms_payment' => $this->getDirectoryCore('terms_payment', $item->terms_payment_id),
            'form_payment' => $this->getDirectoryCore('form_payments', $item->form_payment_id),
            'directory_return' => optional($item->directory_return)->id ? TitleJson::getArray($item->directory_return->title, $this->lang) : null,
            'directory_cancell' =>optional($item->directory_cancell)->id ? TitleJson::getArray($item->directory_cancell->title, $this->lang) : null,
            'description' => $item->description ?? null,
            'responsible' => optional($item->responsible)->full_name ?? null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DocumentPurchasesHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);

        $item->update([
            'organization_convert_id' => $convert_id,
            'document_type' => $this->getDirectoryCore('documents', 1),
        ]);
    }

    public function createOrUpdateDelivery($id)
    {
        if($this->request->delivery){
            $item = $this->getItem($id);
            $item->delivery()->updateOrCreate([
                'document_purchase_id' => $item->id
            ], $this->request->delivery);
        }
    }

    public function updateItem($id)
    {
        $item = $this->modelVisible()->where('document_id', $id)->first();
        $item->update($this->request->all());
        return $item->id;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->makeHidden('supplier','currency');
        return $this->getOneTable($item, DocumentPurchasesHeader::class);
    }

    public function updateCapitalizedDocument($id)
    {
        $item = $this->model()->where('document_id', $id)->first();
        $item->update([
            'description'  => $this->request->description,
            'status_payment_id' => $this->request->status_payment_id,
            'status_product_id' => $this->request->status_product_id,
            'receipt_date_actual' => $this->request->receipt_date_actual
        ]);

        return $item->id;
    }


    public function getDocumentId($id)
    {
        return $this->modelVisible()->where('document_id', $id)->first();
    }

    public function toArchive()
    {
//        foreach ($this->request->data as $item){
//            $data = $this->modelVisible()->where('document_id', $item['id']);
//            $data->archive = Yesno::YES;
//            $data->save();
//        }
    }
}
