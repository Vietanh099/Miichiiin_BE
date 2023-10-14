<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory,SoftDeletes;
    public function scopeActive($query)
    {
        return $query->where('vouchers.status', 1);
    }

    protected $fillable = [
        'name',
        'slug',
        'image',
        'discount',
        'start_at',
        'expire_at',
        'status',
        'description',
        'meta',
        'quantity'
        ];
}
