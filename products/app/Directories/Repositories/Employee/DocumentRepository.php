<?php

namespace App\Directories\Repositories\Employee;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Employees\DirectoryEmployeesDetailsDocument as Model;
use App\Directories\Models\EmployeeDocuments\DirectoryEmployeeDocumentsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class DocumentRepository extends CoreRepository
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

    public function getDocumentsByEmployeeId($id)
    {
        return $this->modelVisible()->where('directory_employee_id', $id)->with('employee');
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

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

        $data->makeVisible([
            'cells',
            'directory_employee_id'
        ]);

        $data = $this->getTable($data, DirectoryEmployeeDocumentsHeader::class);

        return $data->toArray();
    }

    public function createItem()
    {
        return $this->modelVisible()->create($this->request->all());
    }

    public function createOrUpdateCellsById($id)
    {
        $item = $this->modelVisible()->find($id);
        $this->_updateCell($item);
    }

    public function createOrUpdateCellsBySupplier($id)
    {
        $items = $this->getDocumentsByEmployeeId($id)->get();

        foreach ($items as $item){
            $this->_updateCell($item);
        }
    }

    public function _updateCell($item)
    {
        $document_type = null;
        if($item->document_type_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('type_documents', $item->document_type_id))) {
                $document_type = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $data = [
            'employee' => optional($item->employee)->full_name ?? null,
            'document_type' => $document_type,
            'document_number' =>  $item->document_number ?? null,
            'document_issued_date' => $item->document_issued_date ?? null,
            'document_validity_date' => $item->document_validity_date ?? null,
            'document_issued' => $item->document_issued ?? null,
            'date_create_document' => $item->date_create_document ?? null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryEmployeeDocumentsHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function updateItem($id)
    {
        $document_employee = $this->modelVisible()->find($id);
        $document_employee->update($this->request->all());
        return $document_employee;
    }

    public function getItemWithCell($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->makeVisible([
            'cells',
            'directory_employee_id'
        ]);

        return $this->getOneTable($item, DirectoryEmployeeDocumentsHeader::class);
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryEmployeeDocumentsHeader::class);
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
