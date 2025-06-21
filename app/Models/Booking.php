<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'suite',
        'city',
        'area',
        'postal_code',
        'service_date',
        'service_time',
        'frequency_id',
        'cleaning_type_id',
        'square_footage_id',
        'bedrooms_id',
        'bathrooms_id',
        'cleaning_instructions',
        'access_info',
        'parking',
        'property_type',
        'coupon_code',
        'coupon_discount',
        'payment_method',
        'subtotal',
        'discount_amount',
        'total',
        'terms_accepted',
        'status'
    ];

    protected $casts = [
        'service_date' => 'date',
        'coupon_discount' => 'float',
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'total' => 'float',
        'terms_accepted' => 'boolean',
    ];
    protected $dates = [
    'created_at',
    'updated_at',
    'service_date'
];

    public function frequency()
    {
        return $this->belongsTo(Frequency::class);
    }

    public function cleaningType()
    {
        return $this->belongsTo(CleaningType::class);
    }

    public function squareFootage()
    {
        return $this->belongsTo(SquareFootage::class);
    }

    public function bedrooms()
    {
        return $this->belongsTo(Bedroom::class, 'bedrooms_id');
    }

    public function bathrooms()
    {
        return $this->belongsTo(Bathroom::class, 'bathrooms_id');
    }

public function customOptions()
{
    return $this->belongsToMany(CustomOption::class, 'booking_custom_option')
                ->withPivot('any_extra_columns')
                ->withTimestamps();
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
