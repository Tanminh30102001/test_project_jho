<?php
namespace App\Repositories;

use App\Models\PipelineColumn;

class PipelineColumnRepository extends BaseRepository
{
    public function __construct(PipelineColumn $column)
    {
        parent::__construct($column);
    }

    public function getByPipeline($pipelineId)
    {
        return $this->model->where('pipeline_id', $pipelineId)
            ->orderBy('order')
            ->with('opportunities')
            ->get();
    }

    public function updateOrder($columnOrders)
    {
        foreach ($columnOrders as $columnId => $order) {
            $this->model->where('id', $columnId)->update(['order' => $order]);
        }
        return true;
    }
}
