<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'discount_rate'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'frequency_id');
    }
}
