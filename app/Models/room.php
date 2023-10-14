<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class room extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rooms';
    protected $fillable = [
        "id",
        "name",
        "status",
        "id_hotel",
    ];
    public function scopeActive($query)
    {
        return $query->where('rooms.status', 1);
    }
}
