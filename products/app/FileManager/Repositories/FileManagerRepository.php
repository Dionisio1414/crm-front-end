<?php

namespace App\FileManager\Repositories;

use App\FileManager\Models\FileManager as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class DocumentRepository.
 */
class FileManagerRepository extends CoreRepository
{

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

    public function createFile()
    {
        return $this->modelVisible()->updateOrcreate([
            'title' => $this->request->title
        ],$this->request->all());
    }

    public function getFile($id)
    {
        return $this->modelVisible()->find($id);
    }

    public function getFiles($ids)
    {
        return $this->modelVisible()->whereIn('id', $this->request->data)->get();
    }

    public function deleteFiles($ids)
    {
        return $this->modelVisible()->whereIn('id', $ids)->delete();
    }

    public function list()
    {
        /* Get Queries */
        $query = $this->request->query();

        if($query){
            $data = $this->getItemsByQuery($query);
        }else{
            $data = $this->getAll();
        }

        return $data->toArray();
    }
    public function getItemsByQuery($data)
    {
        $files = $this->modelVisible();

        if(isset($data['s'])){
            $files = $files->where('title', 'like', '%' . $data['s'] . '%');
        }

        if(isset($data['is_image'])){
            $files = $files->where('is_image', $data['is_image'] == 'true' ? Yesno::YES : Yesno::NO);
        }

        if($files){
            return $files->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
        }

        return $this->getAll();
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function delete()
    {
        $data = $this->getFiles($this->request->data);
        $data = $data->makeVisible(['path']);
        $this->deleteFiles($this->request->data);
        return $data;
    }
}
