<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bathroom extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'rate'];
        public function bookings()
{
    return $this->hasMany(Booking::class, 'bathrooms_id');
}
}
