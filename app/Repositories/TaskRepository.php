<?php
namespace App\Repositories;

use App\Models\Task;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    // Lấy tất cả các task có thể áp dụng bộ lọc
    public function getAllTasks(array $filters, $perPage)
    {
        return $this->model
            ->when(!empty($filters['manager_id']), function ($query) use ($filters) {
                $query->where('manager_id', $filters['manager_id']);
            })
            ->when(!empty($filters['created_by']), function ($query) use ($filters) {
                $query->where('created_by', $filters['created_by']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', 'like', "%{$filters['status']}%");
            })
            ->when(!empty($filters['search']), function ( $query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
                });
            })
            ->paginate($perPage);
    }
    public function deleteMultiple(array $taskIds)
    {
        return $this->model->whereIn('id', $taskIds)->delete();
    }
    public function updateMultiple(array $taskIds, array $data)
    {
        return $this->model->whereIn('id', $taskIds)->update($data);
    }
}
