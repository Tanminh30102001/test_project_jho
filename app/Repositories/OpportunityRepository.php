<?php

namespace App\Repositories;

use App\Models\Opportunity;

use Illuminate\Pagination\LengthAwarePaginator;

class OpportunityRepository extends BaseRepository{
    public function __construct(Opportunity $model)
    {
        parent::__construct($model);
    }

    public function filter(array $filters)
    {
        $query = $this->model::query();

        if (isset($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }
        if (isset($filters['manager'])) {
            $query->where('manager_id', $filters['manager']);
        }
        if (isset($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (isset($filters['tag'])) {
            $query->whereHas('tags', function ($query) use ($filters) {
                $query->where('name', 'like', "%{$filters['tag']}%");
            });
        }
        return $query->get();
    }

    public function getAll(array $filters,$perPage){
        return $this->model
        ->when(!empty($filters['created_at']), function ( $query) use ($filters) {
            $query->whereDate('created_at', $filters['created_at']);
        })
        ->when(!empty($filters['created_by']), function ($query) use ($filters) {
            $query->where('created_by', $filters['created_by']);
        })
        ->when(!empty($filters['manager_id']), function ( $query) use ($filters) {
            $query->where('manager_id', $filters['manager_id']);
        })
        ->when(!empty($filters['search']), function ( $query) use ($filters) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%");
            });
        })
        ->when(!empty($filters['tags']), function ($query) use ($filters) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        })
        ->paginate($perPage);
    }
    public function addOpportunityToPipelineColumn($opportunityId, $pipelineColumnId)
    {
        $opportunity = $this->model->find($opportunityId);

        if ($opportunity) {
            $opportunity->pipeline_column_id = $pipelineColumnId;
            $opportunity->save();
        }

        return $opportunity;
    }
    public function addTagsToOpportunity($opportunityId, array $tagIds)
    {
        $opportunity = $this->findById($opportunityId);
        if ($opportunity) {
          return  $opportunity->tags()->syncWithoutDetaching($tagIds);
        }
        return null;
    }

    public function removeTagsFromOpportunity($opportunityId, array $tagIds)
    {
        $opportunity = $this->findById($opportunityId);
        if ($opportunity) {
          return  $opportunity->tags()->detach($tagIds);  // Xóa tags khỏi Opportunity
        }
        return null;
    }

    public function getTagsForOpportunity($opportunityId)
    {
        $opportunity = $this->findById($opportunityId);
        return $opportunity ? $opportunity->tags : [];
    }
}
