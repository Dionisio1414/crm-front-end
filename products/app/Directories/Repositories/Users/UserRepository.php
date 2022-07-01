<?php

namespace App\Directories\Repositories\Users;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Users\DirectoryUser as Model;
use App\Directories\Models\Users\DirectoryUsersHeader;
use App\Directories\Models\CompaniesDepartments\DirectoryCompaniesDepartment;
use App\Directories\Models\Positions\DirectoryPositionsDetail;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class UserRepository extends CoreRepository
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
        $data = $this->modelVisible()
            ->without(['cells'])
            ->where(function ($query){
                if($gateway_user_id = $this->request->gateway_user_id) {
                    $query->where('gateway_user_id', $gateway_user_id);
                }
            })
            ->paginate($this->request->paginate);

        $data->data = $this->getParamFromJson($data,[
            'position',
            'department'
        ],
            'employee'
        );

        return $data->toArray();
    }

    public function getItemsByQuery($data)
    {
        $users = $this->modelVisible();

        if(isset($data['s'])){
            $users = $this->searchTitleByJsonRelaion($users, 'cells', $data['s']);
        }

        if(isset($data['active_employees'])){
            $users = $this->whereCellByHeaderRelation($users, DirectoryUsersHeader::class, 'is_not_active', $data['active_employees'] == 'true' ? Yesno::YES : Yesno::NO);
        }

        if(isset($data['invited_users'])){
            $users = $this->whereRelation($users,'is_invited', $data['invited_users'] == 'true' ? Yesno::YES : Yesno::NO);
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

        $data = $this->getTable($data, DirectoryUsersHeader::class);

        return $data->toArray();
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id);
        $employee = $item->employee;

        $full_name = $employee->last_name . ' ' . $employee->first_name . ' ' . $employee->middle_name;

        $data = [
            'full_name' => $full_name,
            'is_not_active' => Yesno::NO,
            'position' => optional($employee->position)->title ? TitleJson::getArray($employee->position->title, $this->lang) : null,
            'department' => optional($employee->department)->title ? TitleJson::getArray($employee->department->title, $this->lang) : null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryUsersHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function createItem($data)
    {
        return $this->modelVisible()->create($data);
    }

    public function updateItem($id, $data = null)
    {
        $item = $this->modelVisible()->find($id);
        if(!$data){
            $item->employee->update($this->request->all());
            if(isset($this->request->is_invited)){
                $item->update($this->request->all());
            }
        }else{
            $item->employee->update($data);
            if(isset($data['is_invited'])){
                $item->update($data);
            }
        }

        return $item;
    }

    public function updateItemByGatewayUserId($id)
    {
        $item = $this->whereRelation($this->modelVisible(), 'gateway_user_id', $id);
        $item->employee->update($this->request->all());

        return $item;
    }


    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryUsersHeader::class);
    }

    public function getItemByEmployeeId($id)
    {
        return  $this->whereRelation($this->modelVisible(), 'directory_employee_id', $id)->first();
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
