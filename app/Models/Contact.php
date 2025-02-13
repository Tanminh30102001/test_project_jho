<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'manager_id','created_by'];
    public function manager() {
        return $this->belongsTo(Manager::class);
    }
    public function opportunities() {
        return $this->hasMany(Opportunity::class);
    }
    public function tags()
{
    return $this->belongsToMany(Tag::class, 'contact_tags', 'contact_id', 'tag_id');
}
public function lists()
{
    return $this->belongsToMany(ListModel::class, 'contact_lists', 'list_id', 'contact_id');
}
}
