<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_withdrawals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);                     // requested amount
            $table->decimal('fee', 12, 2)->default(0);            // 2% admin fee
            $table->decimal('net_amount', 12, 2);                 // amount - fee
            $table->string('status')->default('pending');        // pending, approved, rejected, cancelled
            $table->json('bank_details')->nullable();            // account name, number, bank, etc.
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
};