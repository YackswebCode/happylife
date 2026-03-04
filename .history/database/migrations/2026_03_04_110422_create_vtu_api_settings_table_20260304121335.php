<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vtu_api_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('API provider name (e.g., Payscribe)');
            $table->string('base_url')->comment('Base URL for API (sandbox/live)');
            $table->string('api_key')->comment('Bearer token');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vtu_api_settings');
    }
};