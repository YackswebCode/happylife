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
      Schema::create('repurchase_products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('category');
        $table->decimal('price', 12, 2);
        $table->integer('pv_value');
        $table->integer('stock');
        $table->string('image');
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->string('sku')->unique();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repurchase_products');
    }
};
