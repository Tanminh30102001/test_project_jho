<?php 
namespace App\Repositories;

use App\Models\Pipeline;

class PipelineRepository  extends BaseRepository
{
    public function __construct(Pipeline $model)
    {
        parent::__construct($model); 
    }
    public function getAll()
    {
        return Pipeline::with('columns.opportunities')->get();
    }

    
}
