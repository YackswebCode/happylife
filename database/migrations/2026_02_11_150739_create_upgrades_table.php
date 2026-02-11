<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_upgrades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('old_package_id')->constrained('packages');
            $table->foreignId('new_package_id')->constrained('packages');
            $table->decimal('difference_amount', 12, 2);
            $table->string('payment_method')->default('commission_wallet');
            $table->string('status')->default('completed');
            $table->string('reference')->unique()->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('upgrades');
    }
};