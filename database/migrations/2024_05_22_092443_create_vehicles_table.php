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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number');
            $table->string('engine_number');
            $table->string('chassis_number');
            $table->string('model');
            $table->string('type_of_body')->nullable();
            $table->string('horse_power')->nullable();
            $table->string('year_manufactured')->nullable();
            $table->string('year_of_purchased')->nullable();
            $table->string('passenger_carrying_capacity')->nullable();
            $table->string('goods_carrying_capacity')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
