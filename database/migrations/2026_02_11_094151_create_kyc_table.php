<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kyc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Document type (e.g. national_id, passport, driver_license, utility_bill)
            $table->string('document_type');
            
            // File paths for the uploaded documents
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->string('selfie_image')->nullable();  // optional: user with ID
            
            // Additional fields
            $table->string('id_number')->nullable();     // identification number
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('place_of_issue')->nullable();
            
            // Status tracking
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_comment')->nullable();    // reason if rejected
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kyc');
    }
};