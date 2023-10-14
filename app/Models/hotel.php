<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class hotel extends Model
{
    use HasFactory, SoftDeletes;
    public function scopeActive($query)
    {
        return $query->where('hotels.status', 1);
    }
    protected $table = 'hotels';
    protected $fillable = [
        "id",
        "name",
        "address",
        "description",
        "id_city",
        "quantity_of_room",
        "star",
        "email",
        "phone",
        "status",
        "quantity_floor",
    ];
}
