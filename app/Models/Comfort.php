<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comfort extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'comforts';


    protected $fillable = ['name', 'description','status','icon'];
    public function scopeActiveCategoryRooms($query)
    {
        return $query->where('comforts.status', 2);
    }
}
