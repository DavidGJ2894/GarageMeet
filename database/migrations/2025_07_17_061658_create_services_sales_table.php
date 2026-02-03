<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services_sales', function (Blueprint $table) {
            $table->id('services_sales_id');
            $table->unsignedBigInteger('payment_types_id');
            $table->unsignedBigInteger('employees_id');
            $table->unsignedBigInteger('mechanical_workshops_id');
            $table->dateTime('date');
            $table->double('price', 8, 2);
            $table->unsignedBigInteger('vehicles_id');
            $table->foreign('payment_types_id')->references('payment_types_id')->on('payment_types');
            $table->foreign('mechanical_workshops_id')->references('id')->on('mechanical_workshops');
            $table->foreign('vehicles_id')->references('vehicles_id')->on('vehicles');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_sales');
    }
};
