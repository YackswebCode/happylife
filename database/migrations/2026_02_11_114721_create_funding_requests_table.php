<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_funding_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('funding_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // bank_transfer, online
            $table->string('transaction_id')->nullable()->unique(); // for online payments
            $table->string('proof')->nullable(); // file path
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, approved, declined
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('funding_requests');
    }
};