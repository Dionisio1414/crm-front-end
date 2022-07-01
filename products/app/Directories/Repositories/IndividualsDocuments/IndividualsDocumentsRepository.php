<?php

namespace App\Directories\Repositories\IndividualsDocuments;

use App\Suppliers\Models\SupplierContact as Model;
use App\Directories\Models\IndividualsDocuments\DirectoryIndividualsDocumentsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Core\Helpers\TitleJson;
use App\Suppliers\Models\SupplierContactsDocument;

/**
 * Class PropertyRepository.
 */
class IndividualsDocumentsRepository extends CoreRepository
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
        return $this->model();
    }

    public function modelVisibleDocuments()
    {
        $supplierContactsDocument = new SupplierContactsDocument();
        return $supplierContactsDocument->where('archive',Yesno::NO);
    }

    public function getVisibleDocuments($id)
    {
        $supplierContactsDocument = new SupplierContactsDocument();
        return $supplierContactsDocument
                    ->where('archive',Yesno::NO)
                    ->where('id',$id)
                    ->first();
    }

    public function getAll()
    {
        return $this->modelVisibleDocuments()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisibleDocuments(), 'cells', $s);
    }

    public function getItemsTable()
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s);
        }else{
            $data = $this->getAll();
        }

        $data->makeVisible([
            'cells',
            'supplier_contact_id',
            'counterparty_id'
        ]);

        $data = $this->getTable($data, DirectoryIndividualsDocumentsHeader::class);
        return $data->toArray();
    }

    public function createItem()
    {
        $item = $this->modelVisibleDocuments()->create($this->request->all());
        return $this->getItem($item->id);
    }

    public function createOrUpdateCells($contacts)
    {
        foreach ($contacts as $contact){
            $supplierContact = $this->getItemContact($contact->id);
            if(isset($contact->documents)){
                foreach ($contact->documents as $document) {
                    $this->_updateCell($supplierContact, $document);
                }
            }
        }
    }

    public function createOrUpdateCellsContact($contact_id, $document)
    {
        $supplierContact = $this->getItemContact($contact_id);
        $this->_updateCell($supplierContact, $document);
    }

    public function _updateCell($supplierContact, $document)
    {
        $document_type = null;
        if ($document->document_type_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('type_documents', $document->document_type_id))) {
                $document_type = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $data = [
            'individual' => $supplierContact->full_name ?? null,
            'counterparty' => optional($supplierContact->counterparty)->title ? TitleJson::getArray($supplierContact->counterparty->title, $this->lang) : null,
            'document_type' => $document_type,
            'document_number' => $document->document_number ?? null,
            'document_issued_date' => $document->document_issued_date ?? null,
            'document_validity_date' => $document->document_validity_date ?? null,
            'document_issued' => $document->document_issued ?? null,
            'date_create_document' => $document->date_create_document ?? null,
        ];
        $data = $this->changeCellsCustomFormatBd($data, DirectoryIndividualsDocumentsHeader::class);
        $document->cells()->delete();
        $document->cells()->createMany($data);
    }

    public function updateItem($id)
    {
        $item = $this->modelVisibleDocuments()->find($id);
        $item->update($this->request->all());
        return $this->getItem($item->id);
    }

    public function getItemContact($id)
    {
        return $this->modelVisible()->find($id);
    }

    public function getItem($id)
    {
        $item = $this->modelVisibleDocuments()->find($id);
        $item->makeVisible([
            'cells',
            'supplier_contact_id',
            'counterparty_id'
        ]);

        return $this->getOneTable($item, DirectoryIndividualsDocumentsHeader::class);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            /* Get to archive documents */
            $data = $this->modelVisibleDocuments()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }
}
