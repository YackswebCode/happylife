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
       Schema::create('vtu_plans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('provider_id')->constrained('vtu_providers')->onDelete('cascade');
        $table->string('name');
        $table->string('code')->unique();
        $table->string('service_type'); // data, airtime, cable, electricity
        $table->decimal('amount', 12, 2);
        $table->string('validity');
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vtu_plans');
    }
};
