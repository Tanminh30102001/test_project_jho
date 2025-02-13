<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PipelineColumn extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'pipeline_id', 'order'];
    public function pipeline() {
        return $this->belongsTo(Pipeline::class);
    }
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'pipeline_column_id');
    }
}
