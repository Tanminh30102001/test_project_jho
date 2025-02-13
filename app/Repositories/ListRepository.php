<?php
namespace App\Repositories;

use App\Models\ListModel;

class ListRepository extends BaseRepository
{
    public function __construct(ListModel $model)
    {
        parent::__construct($model);
    }
     public function getAll($perPage = null)
    {
        if ($perPage) {
            return $this->model::paginate($perPage);
        }

        return $this->model::all();
    }
    public function addContacts($listId, array $contactIds)
    {
        $list = $this->model->find($listId);
        if (!$list) {
            return null;
        }

        $list->contacts()->syncWithoutDetaching($contactIds);
        return $list->contacts;
    }


    public function getContacts($listId)
    {
        $list = $this->model->with('contacts')->find($listId);
        return $list ? $list->contacts : null;
    }
    public function updateContacts($listId, array $contactIds)
    {
    $list = $this->model->find($listId);
    if (!$list) {
        return null;
    }

    $list->contacts()->sync($contactIds);
    return $list->contacts;
    }

    public function removeContact($listId, $contactId)
    {
        $list = $this->model->find($listId);
        if (!$list) {
            return false;
        }

        return $list->contacts()->detach($contactId);
    }
}
