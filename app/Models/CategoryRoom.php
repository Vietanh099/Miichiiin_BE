<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class categoryRoom extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'category_rooms';
    protected $fillable = [
        "id",
        "name",
        "image",
        "description",
        "floor",
        "acreage",
        "price",
        "likes",
        "views",
        "status",
        "id_cate",
        "quantity_of_people",
    ];
    public function scopeActiveCategoryRooms($query)
    {
        return $query->where('category_rooms.status', 2);
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'id_cate');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_cate');
    }
    // public function scopeActiveBookings($query)
    // {
    //     return $query->whereHas('bookings', function ($query) {
    //         $query->whereNotIn('status', [2, 3]);
    //     });
    // }
}
