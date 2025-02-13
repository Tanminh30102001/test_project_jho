<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email'];
    public function contacts() {
        return $this->hasMany(Contact::class);
    }
    public function opportunities() {
        return $this->hasMany(Opportunity::class);
    }
}
