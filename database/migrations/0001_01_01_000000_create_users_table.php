<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('username')->unique();
    $table->string('phone')->nullable();
    $table->string('password');
    $table->string('sponsor_id')->nullable();
    $table->string('placement_id')->nullable();
    $table->string('placement_position')->nullable();
    $table->foreignId('package_id')->nullable()->constrained('packages');
    $table->string('country')->nullable();
    $table->string('state')->nullable();
    $table->foreignId('pickup_center_id')->nullable()->constrained('pickup_centers');
    $table->foreignId('rank_id')->nullable()->constrained('ranks');
    $table->integer('left_count')->default(0);
    $table->integer('right_count')->default(0);
    $table->decimal('total_pv', 12, 2)->default(0);
    $table->decimal('current_pv', 12, 2)->default(0);
    $table->string('status')->default('active');
    $table->timestamp('registration_date')->nullable();
    $table->enum('role', ['admin', 'company', 'member'])->default('member');
    $table->rememberToken();
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};