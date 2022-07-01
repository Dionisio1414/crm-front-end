<?php

namespace App\Directories\Repositories\Employees;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Employees\DirectoryEmployee as Model;
use App\Directories\Models\Employees\DirectoryEmployeesHeader;
use App\Directories\Models\CompaniesDepartments\DirectoryCompaniesDepartmentsEmployee;
use App\Directories\Models\Positions\DirectoryPositionsDetail;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Directories\Repositories\Employee\DocumentRepository;

/**
 * Class PropertyRepository.
 */
class EmployeeRepository extends CoreRepository
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
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function list()
    {
        $data = $this->model()
            ->without(['cells', 'contact', 'documents'])
            ->with('position')
            ->where('archive', Yesno::NO)
            ->where(function ($query){
                if($this->request->without_company_department_id){
                    $query->whereNull('company_department_id');
                }
            })
            ->paginate($this->request->paginate);

        $data->data = $this->getParamFromJson($data,[
            'position'
        ]);

        return $data->toArray();
    }

    public function getItemsByQuery($data)
    {
        $users = $this->modelVisible();

        if(isset($data['s'])){
            $users = $this->searchTitleByJsonRelaion($users, 'cells', $data['s']);
        }

        if(isset($data['active_employees'])){
            $users = $this->whereCellByHeaderRelation($users, DirectoryEmployeesHeader::class, 'is_not_active', $data['active_employees'] == 'true' ? Yesno::YES : Yesno::NO);
        }

        if($users){
            return $users->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
        }

        return $this->getAll();
    }

    public function getItemsTable()
    {
        /* Get Queries */
        $query = $this->request->query();

        if($query){
            $data = $this->getItemsByQuery($query);
        }else{
            $data = $this->getAll();
        }

        $data = $this->getTable($data, DirectoryEmployeesHeader::class);

        return $data->toArray();
    }

    public function createItem()
    {
        return $this->modelVisible()->create($this->request->all());
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->with([
            'position',
            'department'
        ])->find($id);

        $contactCell = null;
        if($item->contact){
            $contactCell = $item->contact->mobile_phone . "," . $item->contact->email;
        }

        $data = [
            'full_name' => $item->full_name,
            'is_user' => $item->is_user ? Yesno::YES : Yesno::NO,
            'is_not_active' => optional($item->main)->is_not_active ? Yesno::YES : Yesno::NO,
            'position' =>  optional($item->position)->title ? TitleJson::getArray($item->position->title, $this->lang) : null,
            'inn' => optional($item->main)->inn ? $item->main->inn : null,
            'salary' => optional($item->main)->salary ? $item->main->salary : null,
            'department' => optional($item->department)->title ? TitleJson::getArray($item->department->title, $this->lang) : null,
            'date_receipt' => optional($item->main)->date_receipt ?? null,
            'date_dismissal' => optional($item->main)->date_dismissal ?? null,
            'contact' => $contactCell,
            'date_of_birth' => $item->date_of_birth,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryEmployeesHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);

        if($item->department){
            $company_id = optional($item->department)->id;
            $item->update(['company_department_id'=>$company_id]);
            $this->addToCompanyDepartment($id, $company_id);
        }

        if($item->documents){
            /* Update Or Create directory employees document cells */
            $this->createOrUpdateDirectoryEmployeeDocumentsCells($item->id);
        }
    }

    public function createOrUpdateMain($id)
    {
        if($this->request->main){
            $item = $this->getItem($id);
            $item->main()->updateOrCreate([
                'directory_employee_id'=>$item->id
            ], $this->request->main);
        }
    }

    public function createOrUpdateContact($id)
    {
        if($this->request->contact){

            $item = $this->getItem($id);
            $item->contact()->updateOrCreate([
                'directory_employee_id'=>$item->id
            ], $this->request->contact);
        }
    }

    public function createOrUpdateDocuments($id)
    {
        if($this->request->documents){

            $item = $this->getItem($id);

            if(count($this->request->documents)){
                foreach($this->request->documents as $document){
                    if(isset($document['id'])){
                        $item->documents()->updateOrCreate(['id'=>$document['id']], $document);
                    }else{
                        $item->documents()->create($document);
                    }
                }
            }else{
                $item->documents()->delete();
            }
        }
    }

    public function updateItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->update($this->request->all());
        return $item;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        if($item){
            return $this->getOneTable($item, DirectoryEmployeesHeader::class);
        }

        return null;
    }

    public function addToCompanyDepartment($id, $company_id = null)
    {
        if($company_id){
            DirectoryCompaniesDepartmentsEmployee::updateOrCreate(['employee_id' => $id],[
                'company_department_id'=>$company_id,
                'employee_id'=>$id,
            ]);
        }
    }

    /* Remove from companies_departmetns employee */
    public function _removeFromCompanyDepartments($company_id)
    {
        $directory_employee = DirectoryCompaniesDepartmentsEmployee::where('company_department_id', $company_id)->first();
        $directory_employee->delete();
    }

    public function createOrUpdateDirectoryEmployeeDocumentsCells($id)
    {
        $documentRepository = new DocumentRepository();
        $documentRepository->createOrUpdateCellsBySupplier($id);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);

            /* Remove from companies_departmetns employee*/
            if($data->company_department_id){
                $this->_removeFromCompanyDepartments($data->company_department_id);
            }

            $data->archive = Yesno::YES;
            $data->save();
        }
    }
}
