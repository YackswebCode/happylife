<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_product_claims_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('pickup_center_id')->constrained()->onDelete('cascade');
            $table->string('claim_number')->unique();          // e.g. CLM-20250211-0001
            $table->enum('status', ['pending', 'approved', 'rejected', 'collected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('claimed_at')->nullable();       // when user submitted claim
            $table->timestamp('approved_at')->nullable();      // when admin approved
            $table->timestamp('collected_at')->nullable();     // when product was picked up
            $table->json('receipt_data')->nullable();          // snapshot of claim details at time of claim
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->unique(['user_id', 'product_id']);         // one claim per product per user
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_claims');
    }
};