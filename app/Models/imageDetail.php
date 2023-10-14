<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imageDetail extends Model
{
    use HasFactory;
    protected $table = 'image_details';
    protected $fillable = [
        "id",
        "id_rooms",
        "id_hotel",
        "id_cate",
        "id_image",
    ];
}

