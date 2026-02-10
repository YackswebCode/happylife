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
      Schema::create('ranks', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('level');
        $table->decimal('required_pv', 12, 2);
        $table->decimal('cash_reward', 12, 2);
        $table->string('other_reward')->nullable();
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
        Schema::dropIfExists('ranks');
    }
};
