<?php
// app/Repositories/ContactRepository.php

namespace App\Repositories;

use App\Models\Manager;

use Illuminate\Pagination\LengthAwarePaginator;

class ManagerRepository extends BaseRepository{
    public function __construct(Manager $model)
    {
        parent::__construct($model); // Kế thừa từ BaseRepository
    }
    public function getAll($perPage = 10){
        return parent::getAllNotFilter($perPage);
    }
}
