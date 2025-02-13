<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAllNotFilter($perPage = null)
    {
        if ($perPage) {
            return $this->model::paginate($perPage);
        }

        return $this->model::all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $data['created_by'] = Auth::id();
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        $record->update($data);
        return $record;

    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->delete();
            return true;
        }
        return false;
    }
}
