<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking_custom_option', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('custom_option_id')->constrained('custom_options')->cascadeOnDelete();
            $table->primary(['booking_id', 'custom_option_id']);
            // Add any additional pivot columns here if needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_custom_option');
    }
};
