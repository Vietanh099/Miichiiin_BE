<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class city extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cities';
    protected $fillable = [
        "id",
        "name",
    ];
    public function scopeActiveCategoryRooms($query)
    {
        return $query->where('city.status', 1);
    }
}
