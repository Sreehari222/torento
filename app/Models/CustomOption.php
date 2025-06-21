<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOption extends Model
{
    use HasFactory;

    protected $table = 'custom_options';

    protected $fillable = ['name', 'rate', 'image_path'];

public function bookings()
{
    return $this->belongsToMany(Booking::class, 'booking_custom_option');
}

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
