<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    use HasFactory;

    protected $table = 'lists';
    protected $fillable = ['name'];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_lists', 'list_id', 'contact_id');
    }
}

