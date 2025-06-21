<?php

// database/migrations/xxxx_xx_xx_create_rates_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();

            // Frequency Options
            $table->string('frequency_name');
            $table->decimal('frequency_discount_rate', 8, 2);

            // Cleaning Types
            $table->string('cleaning_type_name');
            $table->decimal('cleaning_type_rate', 8, 2);

            // Square Footage
            $table->string('square_footage_name');
            $table->decimal('square_footage_rate', 8, 2);

            // Bathrooms
            $table->string('bathrooms_name');
            $table->decimal('bathrooms_rate', 8, 2);

            // Bedrooms
            $table->string('bedrooms_name');
            $table->decimal('bedrooms_rate', 8, 2);

            // Custom Cleaning Options
            $table->string('custom_option_name');
            $table->decimal('custom_option_rate', 8, 2);

            // Image Upload
            $table->string('image_path')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
