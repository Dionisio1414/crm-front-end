<?php

namespace App\Leads\Repositories;

use App\Core\Helpers\TitleJson;
use App\Core\Repositories\Traits\RepositoryNomenclatureTraits;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Leads\Models\EditLeadHeader;
use App\Leads\Models\Lead as Model;
use App\Leads\Models\LeadsFailureHeader;
use App\Leads\Models\LeadsHeader;
use App\Leads\Models\ShowLeadHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;
use Carbon\Carbon;

/**
 * Class LeadRepository
 */
class LeadRepository extends CoreRepository
{
    use RepositoryTraits, RepositoryNomenclatureTraits;

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
        return $this->model()->orderBy('created_at', 'DESC')->where('archive', Yesno::NO)->where('is_failure', Yesno::NO);
    }

    public function modelFailure()
    {
        return $this->model()->orderBy('created_at', 'DESC')->where('archive', Yesno::NO)->where('is_failure', Yesno::YES);
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->makeHidden(['cells']);
        $rezult = $data->toArray();

        $new_data = [];

        foreach ($rezult['data'] as $key => $item) {
            $new_data['full_names'][] = [
                'id' => $item['id'],
                'title' => $item['full_name']
            ];
        }

        $rezult['data'] = $new_data;

        return $rezult;
    }

    public function getListWithFull($id)
    {
        return $this->modelVisible()
            ->without('cells')
            ->with([
                'categories',
                'employee',
                'leadStatus',
                'organization',
                'categories',
                'nomenclatures',
                'delivery'
            ])->find($id);
    }

    public function getList($id)
    {
        $data = $this->getListWithFull($id);
        $nomenclatures = $this->getListTable($data);

        if (isset($nomenclatures['arr']['product'])) {
            foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                $nomenclature_array['product'][] = $nomenclature;
            }
        }

        if (isset($nomenclatures['arr']['service'])) {
            foreach ($nomenclatures['arr']['service'] as $nomenclature) {
                $nomenclature_array['service'][] = $nomenclature;
            }
        }

        $data->makeHidden(['nomenclatures']);
        $rezult = $data->toArray();

        $rezult['sum_price'] = $nomenclatures['sum_price'];
        $rezult['sum_balance'] = $nomenclatures['sum_balance'];
        $rezult['sum_product_balance'] = $nomenclatures['sum_product_balance'];
        $rezult['sum_service_balance'] = $nomenclatures['sum_service_balance'];
        $rezult['sum_product_price'] = $nomenclatures['sum_product_price'];
        $rezult['sum_service_price'] = $nomenclatures['sum_service_price'];
        $rezult['sum_discount_price'] = $nomenclatures['sum_discount_price'];

        $rezult['nomenclatures'] = [
            'headers' => TitleJson::get(ShowLeadHeader::get(), $this->lang),
            'body' => $nomenclature_array,
        ];

        return $rezult;
    }

    public function getListEdit($id)
    {
        $data = $this->getListWithFull($id);
        $nomenclatures = $this->getListTable($data);

        if (isset($nomenclatures['arr']['product'])) {
            foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                $nomenclature_array['product'][] = $nomenclature;
            }
        }

        if (isset($nomenclatures['arr']['service'])) {
            foreach ($nomenclatures['arr']['service'] as $nomenclature) {
                $nomenclature_array['service'][] = $nomenclature;
            }
        }

        $data->makeHidden(['nomenclatures']);
        $rezult = $data->toArray();

        $rezult['sum_price'] = $nomenclatures['sum_price'];
        $rezult['sum_balance'] = $nomenclatures['sum_balance'];
        $rezult['sum_product_balance'] = $nomenclatures['sum_product_balance'];
        $rezult['sum_service_balance'] = $nomenclatures['sum_service_balance'];
        $rezult['sum_product_price'] = $nomenclatures['sum_product_price'];
        $rezult['sum_service_price'] = $nomenclatures['sum_service_price'];
        $rezult['sum_discount_price'] = $nomenclatures['sum_discount_price'];

        $rezult['nomenclatures'] = [
            'headers' => TitleJson::get(EditLeadHeader::get(), $this->lang),
            'body' => $nomenclature_array,
        ];

        return $rezult;
    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
    }

    public function getItemsByQueryWithWhere($s, $column_name, $column_value)
    {
        return $this->searchTitleByJson($this->modelVisible()->where($column_name, $column_value), 'cells', $s);
    }

    public function getItemsByWhere($column_name, $column_value)
    {
        return $this->modelVisible()->where($column_name, $column_value)->paginate($this->request->paginate);
    }

    public function getItemsTable()
    {
        if (isset($this->request->s) || isset($this->request->lead_status_id)) {
            if (isset($this->request->s) && isset($this->request->lead_status_id)) {
                $data = $this->getItemsByQueryWithWhere($this->request->s, 'lead_status_id', $this->request->lead_status_id);
            } elseif (isset($this->request->s)) {
                $data = $this->getItemsByQuery($this->request->s);
            } elseif (isset($this->request->lead_status_id)) {
                $data = $this->getItemsByWhere('lead_status_id', $this->request->lead_status_id);
            }
        } else {
            $data = $this->getAll();
        }

        $data = $this->getTable($data, LeadsHeader::class);

        return $data->toArray();
    }

    public function getItemsFailureTable()
    {
        $data = $this->modelFailure()->paginate($this->request->paginate);
        $array = $this->getFailureTable($data);

        return $results = [
            'body' => $array,
            'total_page' => $data->lastPage(),
            'total' => $data->total()
        ];
    }

    public function createItem()
    {
        return $this->modelVisible()->create($this->request->all());
    }

    public function updateItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->update($this->request->all());
        return $item;
    }

    public function createOrUpdateItemCategories($id)
    {
        $item = $this->modelVisible()->find($id);
        $categories = [];
        foreach ($this->request->categories as $key => $value) {
            $categories[$key]['category_id'] = $value['id'];
        }
        $item->categories()->sync($categories);
        return $item;
    }

    public function createOrUpdateItemNomenclatures($id)
    {
        $item = $this->modelVisible()->find($id);
        $nomenclatures = [];
        foreach ($this->request->nomenclatures as $key => $value) {
            $nomenclatures[$key]['nomenclature_id'] = $value['id'];
            $nomenclatures[$key]['balance'] = $value['balance'];
            $nomenclatures[$key]['percent'] = $value['percent'];
            $nomenclatures[$key]['price'] = $value['price'];
        }
        $item->nomenclatures()->sync($nomenclatures);
        return $item;
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id);
        $title_categories = null;
        if (isset($item->categories)) {
            foreach ($item->categories as $value) {
                $title_categories = $title_categories . $value->title . '; ';
            }
        }

        $dataTemp = [
            'date' => $item->date,
            'convert_id' => str_pad($item->id, 8, '0', STR_PAD_LEFT),
            'title' => $item->title . ' : ' . $this->getDirectoryCore('source_attractions', $item->source_attraction_id),
            'lead_status' => optional($item->leadStatus)->title ?? null,
            'phone' => $item->phone ?? null,
            'email' => $item->email ?? null,
            'organization' => optional($item->organization)->title ?? null,
            'loyalty' => $item->loyalty ?? null,
            'categories' => trim($title_categories) ?? null,
            'balance' => $item->nomenclatures->sum('pivot.balance'),
            'percent' => $item->percent ?? null,
            'amount' => $item->nomenclatures->sum('pivot.price'),
            'manager' => trim($item->employee->full_name) ?? null,
            'comments' => $item->comments ?? null
        ];

        $data = $this->changeCellsCustomFormatBd($dataTemp, LeadsHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function createOrUpdateDelivery($id)
    {
        if ($this->request->delivery) {
            $item = $this->getItem($id);
            $item->delivery()->updateOrCreate([
                'lead_id' => $item->id
            ], $this->request->delivery);
        }
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, LeadsHeader::class);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item) {
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }

    public function toFailure()
    {
        foreach ($this->request->data as $item) {
            $data = $this->modelVisible()->find($item['id']);
            $data->is_failure = Yesno::YES;
            $data->save();
        }
    }

    public function outFailure()
    {
        foreach ($this->request->data as $item) {
            $data = $this->modelVisible()->find($item['id']);
            $data->is_failure = Yesno::NO;
            $data->save();
        }
    }
}
