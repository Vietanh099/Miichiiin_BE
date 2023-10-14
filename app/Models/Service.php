<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'image',
        'price',
        'status'
    ];
    public function scopeActive($query)
    {
        return $query->where('services.status', 1);
    }
}
