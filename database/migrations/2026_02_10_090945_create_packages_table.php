<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price', 12, 2);
        $table->integer('pv');
        $table->string('product_entitlement');
        $table->decimal('direct_bonus_amount', 12, 2);
        $table->decimal('pairing_cap', 12, 2);
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->integer('order')->default(0);
        $table->timestamps();
    });

    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};