<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryRoom extends Model
{
    use HasFactory;
    protected $table = 'category_rooms';
    protected $fillable = [
        "id",
        "name",
        "description",
        "image",
    ];
}