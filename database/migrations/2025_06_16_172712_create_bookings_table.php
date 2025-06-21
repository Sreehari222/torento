<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('suite')->nullable();
            $table->string('city');
            $table->string('area');
            $table->string('postal_code');
            $table->date('service_date');
            $table->string('service_time');
            $table->foreignId('frequency_id')->constrained();
            $table->foreignId('cleaning_type_id')->constrained();
            $table->foreignId('square_footage_id')->nullable()->constrained();
            $table->foreignId('bedrooms_id')->nullable()->constrained('bedrooms');
            $table->foreignId('bathrooms_id')->nullable()->constrained('bathrooms');
            $table->text('cleaning_instructions')->nullable();
            $table->text('access_info');
            $table->string('parking')->nullable();
            $table->string('property_type');
            $table->string('coupon_code')->nullable();
            $table->float('coupon_discount')->default(0);
            $table->string('payment_method');
            $table->float('subtotal');
            $table->float('discount_amount')->default(0);
            $table->float('total');
            $table->boolean('terms_accepted');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
