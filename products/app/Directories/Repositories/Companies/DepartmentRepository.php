<?php

namespace App\Directories\Repositories\Companies;

use App\Directories\Models\CompaniesDepartments\DirectoryCompaniesDepartment as Model;
use App\Directories\Models\Employees\DirectoryEmployee;
use App\Directories\Models\Employees\DirectoryEmployeesDetail;
use App\Directories\Models\Employees\DirectoryEmployeesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Core\Helpers\TitleJson;
use App\Directories\Models\Positions\DirectoryPositionsDetail;

/**
 * Class PropertyRepository.
 */
class DepartmentRepository extends CoreRepository
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
        return $this->model()->where('archive', Yesno::NO)
                             ->orderBy('is_default','desc')
                             ->orderBy('order','desc');
    }

    public function getAll()
    {
        $data = $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
        foreach ($data as $item){
            $item->title = TitleJson::getArray($item->title, $this->lang);
            $item->code = str_pad($item->code, 4, '0', STR_PAD_LEFT);
            foreach ($item->employees as $employees){
                $employee = DirectoryEmployee::find($employees->employee_id);
                $position = DirectoryPositionsDetail::find($employee->position_id);
                $employees->first_name = $employee->first_name;
                $employees->last_name = $employee->last_name;
                $employees->middle_name = $employee->middle_name;
                $employees->thumbnail = $employee->thumbnail;
                $employees->is_not_active = optional($employee->main)->is_not_active ?? false;

                if($position){
                    $employees->position = TitleJson::getArray($position->title, $this->lang);
                }else{
                    $employees->position = null;
                }
            }
        };
        return $data;
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->makeHidden(['employees']);
        $rezult = $data->toArray();

        foreach ($rezult['data'] as $key=>$item){
            if(isset($item['title'])){
                $rezult['data'][$key]['title'] = TitleJson::getArray($item['title'], $this->lang);
            }
        }
        return $rezult;
    }

//    public function getItemsByQuery($s)
//    {
//        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
//    }

    public function createOrUpdateItem($id=null)
    {
        $data = $this->changeTitleFormatBd();

        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        if($id){
            $this->getItem($id)->update($data);
            $item = $this->getItem($id);
        }else{
            $item = $this->modelVisible()->create($data);
            $item->update(['code'=>$item->id]);
            /* update order */
            $item->update(['order'=>$item->id]);
        }

        return $item;
    }

    public function createOrUpdateEmployees($id)
    {
        $item = $this->getItem($id);
        if(!$item->employees->isEmpty()){
            /* Clear employees company departments */
            $this->_nullableCompanyDepartments($id);
        }

        if($this->request->employees){
            $item->employees()->delete();
            foreach ($this->request->employees as $employee){
                /* Create employees company departments */
                if(isset($employee['employee_id'])){
                    $this->_changeCompanyDepartments($item, $employee['employee_id']);
                }
            }

            $item->employees()->createMany($this->request->employees);
        }else{
            $item->employees()->delete();
        }
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->code = str_pad($item->code, 5, '0', STR_PAD_LEFT);
        $item->title = TitleJson::getArray($item->title, $this->lang);

        if($item->employees){
            foreach ($item->employees as $employees){
                $employee = DirectoryEmployee::find($employees->employee_id);
                $position = DirectoryPositionsDetail::find($employee->position_id);
                $employees->first_name = $employee->first_name;
                $employees->last_name = $employee->last_name;
                $employees->middle_name = $employee->middle_name;
                if($position){
                    $employees->position = TitleJson::getArray($position->title, $this->lang);
                }else{
                    $employees->position = null;
                }
            }
        }

        return $item;
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();

            /* Removed employees company departments */
            $this->_nullableCompanyDepartments($item['id']);
            $data->employees()->delete();
        }
    }

    public function changeDefaultItem()
    {
        return $this->model()->where('is_default', Yesno::YES)->update(['is_default' => Yesno::NO]);
    }

    public function _nullableCompanyDepartments($id)
    {
        $directory_employee = DirectoryEmployee::where('company_department_id', $id);

        $this->_updateCellsOneTable($directory_employee);

        $directory_employee->update(['company_department_id'=>null]);
    }

    public function _changeCompanyDepartments($item, $employee_id)
    {
        $directory_employee = DirectoryEmployee::where('id', $employee_id);

        $this->_updateCellsOneTable($directory_employee, $item->title);

        $directory_employee->update(['company_department_id'=>$item->id]);
    }

    public function _updateCellsOneTable($directory_employee, $title=null)
    {
        $this->updateCellsOneTable(
            $directory_employee,
            DirectoryEmployeesHeader::class,
            DirectoryEmployeesDetail::class,
            'department',
            $title
        );
    }
}
