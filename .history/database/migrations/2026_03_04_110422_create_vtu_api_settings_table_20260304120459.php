<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vtu_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('vtu_transactions', function (Blueprint $table) {
            $table->dropColumn('api_response');
        });
    }
};