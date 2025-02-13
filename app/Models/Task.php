<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description','created_by', 'due_date', 'status', 'opportunity_id', 'contact_id', 'manager_id'];
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
