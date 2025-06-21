<?php

// app/Models/Rate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'frequency_name',
        'frequency_discount_rate',
        'cleaning_type_name',
        'cleaning_type_rate',
        'square_footage_name',
        'square_footage_rate',
        'bathrooms_name',
        'bathrooms_rate',
        'bedrooms_name',
        'bedrooms_rate',
        'custom_option_name',
        'custom_option_rate',
        'image_path',
    ];
}
