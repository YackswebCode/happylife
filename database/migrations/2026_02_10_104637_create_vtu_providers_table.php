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
     Schema::create('vtu_providers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code')->unique();
        $table->string('service_type'); // airtime,data,cable,electricity
        $table->string('logo')->nullable();
        $table->string('api_endpoint')->nullable();
        $table->string('api_key')->nullable();
        $table->string('api_secret')->nullable();
        $table->boolean('status')->default(true);
        $table->decimal('commission_rate', 5, 2)->default(0);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vtu_providers');
    }
};
