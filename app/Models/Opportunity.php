<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;
    protected $table = 'opportunities';
    protected $fillable = ['name', 'contact_id', 'manager_id', 'pipeline_column_id', 'created_by'];
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'opportunity_tags', 'opportunity_id', 'tag_id');
    }
    public function pipelineColumn()
    {
        return $this->belongsTo(PipelineColumn::class, 'pipeline_column_id');
    }
}
