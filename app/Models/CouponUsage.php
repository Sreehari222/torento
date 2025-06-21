<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = ['coupon_id', 'phone_number', 'booking_id'];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
