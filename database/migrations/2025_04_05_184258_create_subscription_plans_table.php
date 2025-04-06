<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('monthly_price', 8, 2);
            $table->integer('calculation_limit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('subscription_plans');
    }
};
