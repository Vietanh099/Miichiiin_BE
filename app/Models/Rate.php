<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rates';

    public function scopeActiveCategoryRooms($query)
    {
        return $query->where('rates.status', 1);
    }
    protected $fillable = ['id_user', 'id_category', 'content', 'rating', 'status'];
}
