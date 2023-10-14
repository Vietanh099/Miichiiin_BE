<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class booking extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'bookings';
    protected $fillable = [
        "id",
        "name",
        "check_in",
        "check_out",
        "people_quantity",
        "cccd",
        "nationality",
        "status",
        "total_amount",
        "message",
        "phone",
        "email",
        "id_user",
    ];

    public function scopeActive($query)
    {
        return $query->whereNotIn('bookings.status', [2, 3]);
    }
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'id_booking');
    }
}
