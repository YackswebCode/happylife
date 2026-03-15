<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('pickup_center_id')->nullable()->constrained('pickup_centers')->onDelete('set null');
            $table->string('state_name')->nullable();
            $table->string('pickup_center_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['pickup_center_id']);
            $table->dropColumn(['state_id', 'pickup_center_id', 'state_name', 'pickup_center_name']);
        });
    }
};