<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comfortDetail extends Model
{
    use HasFactory;

    protected $table = 'comfort_details';
    protected $fillable = [
        "id",
        "id_cate_room",
        "id_comfort",
    ];
}
