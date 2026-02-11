<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');               // credit, debit, withdrawal, transfer, vtu
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->string('reference')->unique();
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->json('metadata')->nullable(); // matches $casts['metadata'] = 'array'
            $table->timestamps();

            // Indexes for faster lookups
            $table->index(['user_id', 'wallet_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};