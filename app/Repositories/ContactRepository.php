<?php
// app/Repositories/ContactRepository.php

namespace App\Repositories;

use App\Models\Contact;

use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository extends BaseRepository{
    public function __construct(Contact $model)
    {
        parent::__construct($model); // Kế thừa từ BaseRepository
    }
    public function getAll(array $filters,$perPage){
        return $this->model
        ->when(!empty($filters['created_at']), function ( $query) use ($filters) {
            $query->whereDate('created_at', $filters['created_at']);
        })
        ->when(!empty($filters['created_by']), function ($query) use ($filters) {
            $query->where('created_by', $filters['created_by']);
        })
        ->when(!empty($filters['email']), function ( $query) use ($filters) {
            $query->where('email', $filters['email']);
        })
        ->when(!empty($filters['manager_id']), function ( $query) use ($filters) {
            $query->where('manager_id', $filters['manager_id']);
        })
        ->when(!empty($filters['search']), function ( $query) use ($filters) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        })
        ->when(!empty($filters['tags']), function ( $query) use ($filters) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        })
        ->when(!empty($filters['lists']), function ( $query) use ($filters) {
            $query->whereHas('lists', function ($q) use ($filters) {
                $q->whereIn('lists.id', $filters['lists']);
            });
        })
        ->paginate($perPage);
    }
}
