<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();

            // Foreign key to coupons table (matches your existing schema)
            $table->foreignId('coupon_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Phone number to track usage per number
            $table->string('phone_number', 20);

            // Optional: Link to booking if applicable
            $table->foreignId('booking_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Timestamp when coupon was used
            $table->timestamp('used_at')->useCurrent();

            $table->timestamps();

            // Unique constraint to prevent duplicate uses per phone number
            $table->unique(['coupon_id', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('coupon_usages');
    }
};
